@extends('layouts.root')

@section('content')

    <div class="ui segment">
        <div class="ui three column grid">
            <div class="column">
                <a class="ui button green basic" href="{{ url()->action('WebsiteController@create') }}">Tạo mới</a>
            </div>
        </div>
        <div class="ui styled accordion">
            @foreach($websites as $website)
            <div class="title active">
                <i class="dropdown icon"></i>
                {{ $website->name }} - {{ $website->is_on ? 'running' : 'stopped' }}
            </div>
            <div class="content">
                <a class="ui button basic green website-detail" href="{{ url()->action('WebsiteController@show', $website->id) }}">More</a>
            </div>
            @endforeach
        </div>
    </div>

@endsection