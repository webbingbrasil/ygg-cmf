<div class="padder-v b-b">
    @isset($title)
        <h4 class="font-thin text-black mb-0">{{ __($title) }}</h4>
    @endisset
    <div class="row padder-v">
        @foreach($metrics as $key => $metric)
            <div class="col  @if(!$loop->last) b-r @endif">
                @if(isset($metric['link']))
                    <a href="{{$metric['link']}}">
                        @endif
                        <small class="text-muted block mb-1">{{ __($key) }}</small>
                        <p class="h4 mb-1 {{Arr::get($metric, 'color', 'text-black')}} font-thin">
                            {{ $metric[$keyValue] }}
                            @if(isset($metric['icon']))
                                <i class="{{$metric['icon']}} v-bottom"></i>
                            @endif
                        </p>

                        @isset($metric[$keyDiff])
                            @if((float)$metric[$keyDiff] < 0)
                                <small class="text-xs text-danger">{{ $metric[$keyDiff] }} % <i
                                            class="icon-arrow-down v-top"></i></small>
                            @elseif((float)$metric[$keyDiff] == 0)
                                <small class="text-xs text-muted">{{ $metric[$keyDiff] }} % <i class="icon-refresh v-top"></i></small>
                            @else
                                <small class="text-xs text-success">{{ $metric[$keyDiff] }} % <i
                                            class="icon-arrow-up v-top"></i></small>
                            @endif
                        @endisset

                        @if(isset($metric['link']))
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>
