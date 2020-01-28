<?php

namespace Ygg\Old\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Class LangController
 * @package Ygg\Old\Http\Controllers
 */
class LangController extends Controller
{
    /**
     * Echoes out the localization messages as a JS file,
     * to be used by the front code (Vue.js).
     */
    public function index()
    {
        $lang = app()->getLocale();
        $version = ygg_version();

        $strings = Cache::rememberForever('ygg.lang.'.$lang.'.'.$version.'.js', function () {
            $strings = [];

            foreach (['action_bar', 'form', 'modals', 'resource_list', 'dashboard'] as $filename) {
                $strings += collect(trans('ygg-front::'.$filename))
                    ->mapWithKeys(function ($value, $key) use ($filename) {
                        return ["$filename.$key" => $value];
                    })->all();
            }

            return $strings;
        });

        return response('window.i18n = '.json_encode($strings).';')
            ->header('Content-Type', 'application/javascript');
    }
}
