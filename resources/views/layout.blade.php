<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="base-url" content="{{ ygg_admin_base_url() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ ygg_page_title($yggMenu ?? null, $resourceKey ?? $dashboardKey ?? null) }}</title>
    <link rel="stylesheet" href="/vendor/ygg/ygg.css?version={{ ygg_version() }}">
    <link rel="stylesheet" href="/vendor/ygg/ygg-cms.css?version={{ ygg_version() }}">
    {!! \Arr::get($injectedAssets ?? [], 'head') !!}
</head>
<body>
    <div id="glasspane"></div>


    @yield('content')

    <script src="/vendor/ygg/manifest.js?version={{ ygg_version() }}"></script>
    <script src="/vendor/ygg/vendor.js?version={{ ygg_version() }}"></script>
    <script src="/vendor/ygg/client-api.js?version={{ ygg_version() }}"></script>

    {!! ygg_custom_fields() !!}

    <script src="/vendor/ygg/lang.js?version={{ ygg_version() }}&locale={{ app()->getLocale() }}"></script>
    <script src="/vendor/ygg/ygg.js?version={{ ygg_version() }}"></script>
</body>
</html>