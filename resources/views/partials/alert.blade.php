@if (session()->has(\Ygg\Alert\Alert::SESSION_MESSAGE))
    <div class="alert alert-{{ session(\Ygg\Alert\Alert::SESSION_LEVEL) }}">
        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-hidden="true">&times;
        </button>
        {!! session(\Ygg\Alert\Alert::SESSION_MESSAGE) !!}

        @yield('flash_notification.sub_message')
    </div>
@endif

@empty(!$errors->count())
    <div class="alert alert-danger" role="alert">
        <strong>{{  __('Oh snap!') }}</strong>
        {{ __('Change a few things up and try submitting again.') }}
        <ul class="m-t-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
