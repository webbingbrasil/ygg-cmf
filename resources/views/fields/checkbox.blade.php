@component($typeForm, get_defined_vars())
    @isset($sendTrueOrFalse)
        <input hidden name="{{$attributes['name']}}" value="{{$attributes['novalue']}}">
        <div class="custom-control custom-checkbox">
            <input value="{{$attributes['yesvalue']}}"
                   @attributes($attributes)
                   @if(isset($attributes['value']) && $attributes['value']) checked @endif
            >
            <label class="custom-control-label" for="{{$id}}">{{$placeholder ?? ''}}</label>
        </div>
    @else
        <div class="custom-control custom-checkbox">
            <input @attributes($attributes)
                   @if(isset($attributes['value']) && $attributes['value'] && (!isset($attributes['checked']) || $attributes['checked'] !== false)) checked @endif
            >
            <label class="custom-control-label" for="{{$id}}">{{$placeholder ?? ''}}</label>
        </div>
    @endisset
@endcomponent
