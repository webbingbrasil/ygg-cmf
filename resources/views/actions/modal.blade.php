@component($typeForm, get_defined_vars())
    <button type="button"
            @attributes($attributes)
            data-action="screen--base#targetModal"
            data-modal-title="{{ $modalTitle ?? $title ??  '' }}"
            data-modal-key="{{ $modal ?? '' }}"
            data-modal-async="{{ $async }}"
            data-modal-params='@json($asyncParameters)'
            data-modal-action="{{ $action }}">
        @if(!empty($icon))<i class="{{ $icon ?? '' }} mr-2"></i>@endif {{ $name ?? '' }}
    </button>
@endcomponent
