<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Article;

class ArticleController extends Controller{
    public function get_articles(){
        Article::query()->truncate();
        $folder_arr = Storage::disk('uploads')->directories();
        foreach($folder_arr as $folder){
            Storage::disk('uploads')->deleteDirectory($folder);
        }
        $url_date = date('Y/m/d');
        $expected_count = 300;
        $article_count = 0;
        while(true){
            $page = 1;
            while(true){
                $res = curl_connect('http://www.tert.am/am/news/'.$url_date.'/'.$page);
                $data = str_get_html($res);
                foreach($data->find('.news-blocks') as $val){
                    $article_count++;
                    $article_ = curl_connect($val->find('h4 a', 0)->href);
                    $article_page = str_get_html($article_);
                    $article_page->find('#item .floatRight', 0)->outertext = '';
                    $title = $article_page->find('#item h1', 0)->innertext;
                    foreach($article_page->find('script') as $key => $vali){
                        $article_page->find('script', $key)->outertext = '';
                    }
                    $article_page->find('#top-likes',0)->outertext = '';
                    $date = explode(' ',$article_page->find('#item .n-d', 0)->innertext);
                    $date = $date[0];
                    $date = str_replace('/', '-', $url_date).' '.$date;
                    $url = $val->find('h4 a', 0)->href;
                    $id = 'article'.ltrim(strrchr($val->find('h4 a', 0)->href, '/'), '/');
                    $images = $article_page->find('#item img');
                    Storage::disk('uploads')->makeDirectory($id);
                    foreach($images as $i => $image){
                        if(strpos(parse_url($image->src, PHP_URL_HOST), 'tert.am') === false){
                            continue;
                        }
                        if(strpos($image->src, '?') !== false){
                            $image->src = substr($image->src, 0, strpos($image->src, '?'));
                        }
                        $img = curl_connect($image->src);
                        $img_name = basename($image->src);
                        Storage::disk('uploads')->put($id.'/'.$img_name, $img);
                        $article_page->find('#item img', $i)->src = '/assets/images/uploads/'.$id.'/'.$img_name;//Storage::disk('uploads')->get($id.'/'.$img_name);
                    }
                    $content = $article_page->find('#item', 0)->innertext;
                    $article = new Article(array(
                        'title' => $title,
                        'content' => $content,
                        'date' => $date,
                        'url' => $url
                    ));
                    $article->save();
                    if($article_count == $expected_count){
                        break;
                    }
                }
                if(is_numeric($data->find('.pagingNavItem', -1)->innertext) !== false){
                    break;
                }
                if($article_count == $expected_count){
                    break 2;
                }
                $page++;
            }
            $url_date = date('Y/m/d', strtotime(str_replace('/','-', $url_date)) - 86400);
        }
        return redirect()->route('articles');

    }

    public function get_show_articles(){
        $articles = Article::orderBy('date')->get();
        return view('articles', array('articles' => $articles));
    }

    public function get_delete_article($id){
        Article::where('id', $id)->delete();
        return redirect()->route('articles');
    }

    public function get_edit_article($id){
        $article = Article::where('id', $id)->first();
        return view('edit_article', array('article' => $article));
    }

    public function post_edit_article(Request $request){
        $this->validate($request, array(
            'title' => 'required|string',
            'id' => 'required|exists:articles'
        ));
        $article = Article::where('id', $request->input('id'))->first();
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->update();
        return redirect()->back();
    }
}
