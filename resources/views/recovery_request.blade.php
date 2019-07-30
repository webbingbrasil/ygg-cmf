@extends('ygg::layout')

@section('content')

    <div id="ygg-app" class="login">
        <div class="container">
            <form method="POST" action="{{ route('ygg.password.email') }}">
                {{ csrf_field() }}
                <div class="row justify-content-center">
                    <div class="col-sm-9 col-md-6 col-lg-5 col-xl-4">

                        <h1 class="text-center mb-3">{{ygg_title()}}</h1>

                        @if ($errors->any())

                            <div role="alert" class="YggNotification YggNotification--error">
                                <div class="YggNotification__details">
                                    <div class="YggNotification__text-wrapper">
                                        <p class="YggNotification__subtitle">@lang('ygg::auth.invalid_email')</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (session('status'))

                            <div role="alert" class="YggNotification YggNotification--success">
                                <div class="YggNotification__details">
                                    <div class="YggNotification__text-wrapper">
                                        <p class="YggNotification__subtitle">{{session('status')}}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="YggModule">
                            <div class="YggModule__inner">
                                <div class="YggModule__content">
                                    <div class="YggForm__form-item YggForm__form-item--row">
                                        <input type="text" name="email" id="email" class="YggText" value="{{ old('email') }}" placeholder="@lang('ygg::login.email_field')">
                                    </div>
                                    <button type="submit" id="submit" class="YggButton YggButton--primary">
                                        @lang('ygg::login.send_reset_link_button')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection