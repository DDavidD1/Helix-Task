@extends('layouts.header')

@section('title')
    Edit | Parser for Helix
@endsection

@section('content')
    <div id="edit_article">
        <div class="container">
            <div class="row">
                <h1>Edit Article</h1>
                @if(count($errors) > 0)
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <form action="{{ route('post_edit_article') }}" method="post">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <textarea name="title" class="form-control" id="title">{{ $article['title'] }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="art_content">Content:</label>
                        <textarea name="content" class="myTextEditor" style="width:100%;" id="art_content">{{ $article['content'] }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="{{ $article['id'] }}" />
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
@endsection