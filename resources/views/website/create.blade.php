@extends('layouts.root')

@section('content')

    <div class="ui segment">

        <form class="ui form" action="{{ url()->action('WebsiteController@store') }}" method="POST">
            {{ csrf_field() }}
            <div class="field">
                <label>Tên Website</label>
                <input type="text" name="name" placeholder="Tên Website">
            </div>
            <div class="field">
                <label>Loại Website</label>
                <select class="ui search dropdown" name="type">
                    <option value="">Chọn loại Website</option>
                    <option value="Wordpress">Wordpress</option>
                    <option value="Laravel">Laravel</option>
                </select>
            </div>
            <button class="ui button" type="submit">Submit</button>
        </form>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
        });
    </script>
@endpush