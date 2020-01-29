@extends('ygg::layout')

@section('content')

    <div id="ygg-app" class="form">
        @include('ygg::partials._menu')
        <ygg-action-view context='form'>
            <ygg-form resource-key="{{ $resourceKey }}"
                        instance-id="{{ $instanceId ?? '' }}">
            </ygg-form>
        </ygg-action-view>
    </div>

@endsection