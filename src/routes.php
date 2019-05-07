<?php

// API routes
Route::group([
    'prefix' => '/'.ygg_admin_base_url().'/api',
    'middleware' => ['ygg_web', 'ygg_api_errors', 'ygg_api_context', 'ygg_api_validation', 'ygg_locale'],
    'namespace' => 'Ygg\Http\Controllers\Api'
], function () {

    Route::get('/dashboard/{dashboardKey}')
        ->name('ygg.api.dashboard')
        ->uses('DashboardController@show');

    Route::get('/dashboard/{dashboardKey}/action/{actionKey}/data')
        ->name('ygg.api.dashboard.action.data')
        ->uses('Actions\DashboardActionController@show');

    Route::post('/dashboard/{dashboardKey}/action/{actionKey}')
        ->name('ygg.api.dashboard.action')
        ->uses('Actions\DashboardActionController@update');

    Route::get('/list/{resourceKey}')
        ->name('ygg.api.list')
        ->middleware(['ygg_api_append_list_authorizations', 'ygg_api_append_list_multiform', 'ygg_save_list_params', 'ygg_api_append_notifications'])
        ->uses('ResourceListController@show');

    Route::post('/list/{resourceKey}/reorder')
        ->name('ygg.api.list.reorder')
        ->uses('ResourceListController@update');

    Route::post('/list/{resourceKey}/state/{instanceId}')
        ->name('ygg.api.list.state')
        ->uses('Actions\ResourceStateController@update');

    Route::post('/list/{resourceKey}/action/{actionKey}')
        ->name('ygg.api.list.action.resource')
        ->uses('Actions\ResourceActionController@update');

    Route::post('/list/{resourceKey}/action/{actionKey}/{instanceId}')
        ->name('ygg.api.list.action.instance')
        ->uses('Actions\InstanceActionController@update');

    Route::get('/list/{resourceKey}/action/{actionKey}/data')
        ->name('ygg.api.list.action.resource.data')
        ->uses('Actions\ResourceActionController@show');

    Route::get('/list/{resourceKey}/action/{actionKey}/{instanceId}/data')
        ->name('ygg.api.list.action.instance.data')
        ->uses('Actions\InstanceActionController@show');

    Route::get('/form/{resourceKey}')
        ->name('ygg.api.form.create')
        ->middleware('ygg_api_append_form_authorizations')
        ->uses('FormController@create');

    Route::get('/form/{resourceKey}/{instanceId}')
        ->name('ygg.api.form.edit')
        ->middleware('ygg_api_append_form_authorizations')
        ->uses('FormController@edit');

    Route::post('/form/{resourceKey}/{instanceId}')
        ->name('ygg.api.form.update')
        ->uses('FormController@update');

    Route::delete('/form/{resourceKey}/{instanceId}')
        ->name('ygg.api.form.delete')
        ->uses('FormController@delete');

    Route::post('/form/{resourceKey}')
        ->name('ygg.api.form.store')
        ->uses('FormController@store');

    Route::post('/download/{resourceKey}/{instanceId}/{formUploadFieldKey}')
        ->name('ygg.api.form.download')
        ->uses('FormDownloadController@show');

    Route::get('/filters')
        ->name('ygg.api.filter.index')
        ->uses('GlobalFilterController@index');

    Route::post('/filters/{filterKey}')
        ->name('ygg.api.filter.update')
        ->uses('GlobalFilterController@update');
});

// Web routes
Route::group([
    'prefix' => '/'.ygg_admin_base_url(),
    'middleware' => ['ygg_web'],
    'namespace' => 'Ygg\Http\Controllers'
], function () {

    Route::get('/login')
        ->name('ygg.login')
        ->uses('LoginController@create');

    Route::post('/login')
        ->name('ygg.login.post')
        ->uses('LoginController@store');

    Route::get('/')
        ->name('ygg.home')
        ->uses('HomeController@index');

    Route::get('/logout')
        ->name('ygg.logout')
        ->uses('LoginController@destroy');

    Route::get('/list/{resourceKey}')
        ->name('ygg.list')
        ->middleware('ygg_restore_list_params')
        ->uses('ListController@show');

    Route::get('/form/{resourceKey}/{instanceId}')
        ->name('ygg.edit')
        ->uses('FormController@edit');

    Route::get('/form/{resourceKey}')
        ->name('ygg.create')
        ->uses('FormController@create');

    Route::get('/dashboard/{dashboardKey}')
        ->name('ygg.dashboard')
        ->uses('DashboardController@show');

    Route::post('/api/upload')
        ->name('ygg.api.form.upload')
        ->uses('Api\FormUploadController@store');
});

// Localization
Route::get('/vendor/ygg/lang.js')
    ->name('ygg.assets.lang')
    ->uses('Ygg\Http\Controllers\LangController@index');
