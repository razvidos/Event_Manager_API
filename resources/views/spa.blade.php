@extends('layouts.app')

@section('content')
    <div id="app" class="app"></div>
    <script>
        window.auth_user = {!! json_encode($auth_user, JSON_THROW_ON_ERROR); !!};
    </script>
@endsection
