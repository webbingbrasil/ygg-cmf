<?php

namespace Ygg\Old\Http\Controllers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class LoginController
 * @package Ygg\Old\Http\Controllers
 */
class LoginController extends Controller
{
    use ValidatesRequests;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $guardSuffix = config('ygg.auth.guard') ? ':'.config('ygg.auth.guard') : '';

        $this->middleware('ygg_guest'.$guardSuffix)
            ->only(['create', 'store']);

        $this->middleware('ygg_auth'.$guardSuffix)
            ->only('destroy');
    }

    /**
     * @return Factory|RedirectResponse|View
     */
    public function create()
    {
        if (config('ygg.auth.login_page_url')) {
            return redirect()->to(config('ygg.auth.login_page_url'));
        }

        return view('ygg::login');
    }

    /**
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'login' => 'required',
            'password' => 'required',
        ]);

        if ($this->guard()->attempt([
            $this->getLoginAttribute() => request('login'),
            $this->getPasswordAttribute() => request('password')
        ])) {
            return redirect()->intended('/'.ygg_admin_base_url());
        }

        return back()->with('invalid', true)->withInput();
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return auth()->guard(config('ygg.auth.guard'));
    }

    /**
     * @return Repository|mixed
     */
    protected function getLoginAttribute()
    {
        return config('ygg.auth.login_attribute', 'email');
    }

    /**
     * @return string
     */
    protected function getPasswordAttribute(): string
    {
        return config('ygg.auth.password_attribute', 'password');
    }

    /**
     * @return RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $this->guard()->logout();

        return redirect()->to(
            config('ygg.auth.login_page_url', route('ygg.login'))
        );
    }
}
