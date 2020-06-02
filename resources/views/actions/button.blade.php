@component($typeForm, get_defined_vars())
    <button @if($disableAction === false) form="post-form"
            formaction="{{ $action }}" @endif
            data-novalidate="{{ var_export($novalidate) }}"
            data-turbolinks="{{ var_export($turbolinks) }}"
            @empty(!$confirm)onclick="return confirm('{{$confirm}}');"@endempty
            @attributes($attributes)>
        @isset($icon)<i class="{{ $icon }} mr-2"></i>@endisset
        {{ $name ?? '' }}
    </button>
@endcomponent
