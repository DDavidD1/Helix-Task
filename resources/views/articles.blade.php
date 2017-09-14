@extends('layouts.header')

@section('title')
    Parser for Helix
@endsection

@section('content')
    <div id="articles">
        <div class="container">
            <div class="row">
                <h1>Articles</h1>
                <table id="articles_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>URL</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $val)
                            <tr>
                                <td>{{ $val['title'] }}</td>
                                <td>{{ $val['date'] }}</td>
                                <td><a href="{{ $val['url'] }}">{{ $val['url'] }}</a></td>
                                <td><a href="{{ route('edit_article', array('id' => $val['id'])) }}"><img width="20" src="{{ URL::to('assets/images/edit.png') }}"></a></td>
                                <td><a href="{{ route('delete_article', array('id' => $val['id'])) }}"><img width="20" src="{{ URL::to('assets/images/delete.png') }}"></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection