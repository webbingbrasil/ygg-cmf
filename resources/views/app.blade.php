<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}" data-controller="layouts--html-load">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Ygg') - @yield('description','Admin')</title>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token" data-turbolinks-permanent>
    <meta name="auth" content="{{  Auth::check() }}"  id="auth" data-turbolinks-permanent>
    @if(file_exists(public_path('/css/ygg/ygg.css')))
        <link rel="stylesheet" type="text/css" href="{{  mix('/css/ygg/ygg.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{  ygg_mix('/css/ygg.css','ygg') }}">
    @endif

    @stack('head')

    <meta name="turbolinks-root" content="{{  Dashboard::prefix() }}">
    <meta name="dashboard-prefix" content="{{  Dashboard::prefix() }}">

    <script src="{{ ygg_mix('/js/manifest.js','ygg') }}" type="text/javascript"></script>
    <script src="{{ ygg_mix('/js/vendor.js','ygg') }}" type="text/javascript"></script>
    <script src="{{ ygg_mix('/js/ygg.js','ygg') }}" type="text/javascript"></script>

    @foreach(Dashboard::getResource('stylesheets') as $stylesheet)
        <link rel="stylesheet" href="{{  $stylesheet }}">
    @endforeach

    @stack('stylesheets')

    @foreach(Dashboard::getResource('scripts') as $scripts)
        <script src="{{  $scripts }}" defer type="text/javascript"></script>
    @endforeach
</head>

<body>


<div class="app row m-n" id="app" data-controller="@yield('controller')" @yield('controller-data')>
    <div class="container">
        <div class="row">
            <div class="aside col-xs-12 col-md-2 offset-xxl-0 col-xl-2 col-xxl-3 no-padder bg-dark">
                <div class="d-md-flex align-items-start flex-column d-sm-block h-full">
                    @yield('body-left')
                </div>
            </div>
            <div class="col-md col-xl col-xxl-9 bg-white b-r box-shadow-lg no-padder min-vh-100">
                @yield('body-right')
            </div>
        </div>
    </div>

</div>

@stack('scripts')


</body>
</html>
