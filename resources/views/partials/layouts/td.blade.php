<td class="text-{{$align}}" style="{{$style}}"  data-column="{{ $slug }}">
    @isset($render)
        @if(is_array($value))
            @foreach($value as $item)
                {!! $item !!}
            @endforeach
        @else
            {!! $value !!}
        @endif
    @else
        {{ $value }}
    @endisset
</td>
