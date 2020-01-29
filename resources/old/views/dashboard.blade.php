@extends('ygg::layout')

@section('content')

    <div id="ygg-app" class="dashboard">
        @include('ygg::partials._menu')

        <ygg-action-view context="dashboard" v-cloak>
            <router-view></router-view>
        </ygg-action-view>
    </div>

@endsection