@extends('ygg::layout')

@section('content')

    <div id="ygg-app" class="Ygg__dashboard-page" v-cloak>
        @include('ygg::partials._menu')

        <div class="YggActionView Ygg__empty-view">
            <div class="container h-100 d-flex justify-content-center align-items-center">
                <h1>
                    @lang('ygg::menu.no-dashboard-message')
                </h1>
            </div>
        </div>

    </div>

@endsection