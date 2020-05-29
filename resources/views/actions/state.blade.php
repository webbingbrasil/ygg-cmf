@component($typeForm, get_defined_vars())
    <button
        @attributes($attributes)
        data-toggle="dropdown"
        aria-expanded="false"
    >
        <i class="{{ $icon ?? 'icon-circle' }} mr-2" style="color: {{ $color ?? 'black' }}"></i>
        {{ $name ?? '' }}
    </button>

    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow bg-white"
         x-placement="bottom-end"
    >
        @foreach($states as $state)
            <button form="post-form"
                    formaction="{{ $action }}&value={{ \Arr::get($state, 'value')  }}"
                    data-novalidate="false"
                    data-turbolinks="true"
                    @empty(!\Arr::get($state, 'confirm', false))onclick="return confirm('{{Arr::get($state, 'confirm', false)}}');"@endempty
                    @attributes($attributes)>
                <i class="{{ \Arr::get($state, 'icon', $icon) ?? 'icon-circle' }} mr-2" style="color: {{ \Arr::get($state, 'color') ?? 'black' }}"></i>
                {{ \Arr::get($state, 'label', false) }}
            </button>
        @endforeach
    </div>
@endcomponent
