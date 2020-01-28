<?php

namespace Ygg\Old\Http\Controllers;

use Illuminate\Routing\Controller;

class ProtectedController extends Controller
{
    /**
     * ProtectedController constructor.
     */
    public function __construct()
    {
        $this->middleware('ygg_auth'.(config('ygg.auth.guard') ? ':'.config('ygg.auth.guard') : ''));
    }
}
