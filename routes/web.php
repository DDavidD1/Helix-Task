<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', array(
    'uses' => 'ArticleController@get_articles',
    'as' => 'home'
));

Route::get('/articles', array(
    'uses' => 'ArticleController@get_show_articles',
    'as' => 'articles'
));

Route::get('/delete-article/{id}', array(
    'uses' => 'ArticleController@get_delete_article',
    'as' => 'delete_article'
));

Route::get('/edit-article/{id}', array(
    'uses' => 'ArticleController@get_edit_article',
    'as' => 'edit_article'
));

Route::post('/edit-article', array(
    'uses' => 'ArticleController@post_edit_article',
    'as' => 'post_edit_article'
));