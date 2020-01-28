<?php

namespace Ygg\Old\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class FormController
 * @package Ygg\Old\Http\Controllers
 */
class FormController extends ProtectedController
{
    /**
     * @param $resourceKey
     * @param $instanceId
     * @return Factory|View
     */
    public function edit($resourceKey, $instanceId)
    {
        return view('ygg::form', compact('resourceKey', 'instanceId'));
    }

    /**
     * @param $resourceKey
     * @return Factory|View
     */
    public function create($resourceKey)
    {
        return view('ygg::form', compact('resourceKey'));
    }
}
