@extends('layouts.root')

@section('content')

    <div class="ui segment">
        <div class="ui three column grid">
            <div class="column">
                <a class="ui button green basic" href="{{ url()->action('WebsiteController@create') }}">Tạo mới</a>
            </div>
        </div>
        <div class="ui three column grid">
            @foreach($websites as $website)
                <div class="column">
                    <div class="ui segment {{ $website->is_on ? 'green' : 'red' }}">
                        <h3>{{ $website->name }}</h3>
                        <p>Status: {{ $website->is_on ? 'running' : 'stopped' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection