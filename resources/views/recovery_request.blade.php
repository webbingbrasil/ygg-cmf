@extends('ygg::layout')

@section('content')

    <div id="ygg-app" class="login">
        <div class="container">
            <form method="POST" action="{{ route('ygg.login.post') }}">
                {{ csrf_field() }}
                <div class="row justify-content-center">
                    <div class="col-sm-9 col-md-6 col-lg-5 col-xl-4">

                        <h1 class="text-center mb-3">{{ygg_title()}}</h1>

                        @if ($errors->any())

                            <div role="alert" class="YggNotification YggNotification--error">
                                <div class="YggNotification__details">
                                    <div class="YggNotification__text-wrapper">
                                        <p class="YggNotification__subtitle">@lang('ygg::auth.validation_error')</p>
                                    </div>
                                </div>
                            </div>

                        @elseif (session()->has("invalid"))

                            <div role="alert" class="YggNotification YggNotification--error">
                                <div class="YggNotification__details">
                                    <div class="YggNotification__text-wrapper">
                                        <p class="YggNotification__subtitle">@lang('ygg::auth.invalid_credentials')</p>
                                    </div>
                                </div>
                            </div>

                        @endif
                        <div class="YggModule">
                            <div class="YggModule__inner">
                                <div class="YggModule__content">
                                    <div class="YggForm__form-item YggForm__form-item--row">
                                        <input type="text" name="login" id="login" class="YggText" value="{{ old('login') }}" placeholder="@lang('ygg::login.login_field')">
                                    </div>

                                    <div class="YggForm__form-item YggForm__form-item--row">
                                        <input type="password" name="password" id="password" class="YggText" placeholder="@lang('ygg::login.password_field')">
                                    </div>
                                    <button type="submit" id="submit" class="YggButton YggButton--primary">
                                        @lang('ygg::login.button')
                                    </button>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('ygg.password.request') }}">@lang('ygg::login.recovery-password-link')</a>

                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
