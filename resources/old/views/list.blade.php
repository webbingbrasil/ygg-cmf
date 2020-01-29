@extends('ygg::layout')

@section('content')

    <div id="ygg-app">
        @include('ygg::partials._menu')
        <ygg-action-view context="list">
            <router-view></router-view>
        </ygg-action-view>
    </div>

@endsection