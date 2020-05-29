<div data-resource-id="{{ $resource->id }}" class="row">
    <!-- hbox layout -->
    <div class="hbox hbox-auto-xs no-gutters">
        @if(count($type->fields()) > 0)
        <!-- column -->
        <div class="hbox-col">
            <div class="vbox">
                <div class="wrapper">
                    @if($hasLocales = ($locales->count() > 1))
                    <div class="nav-tabs-alt">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($locales as $code => $lang)
                            <li class="nav-item">
                                <a class="nav-link @if($loop->first)active @endif"
                                   data-action="screen--tabs#setActiveTab"
                                   data-target="#local-{{$code}}"
                                   id="button-tab-options"
                                   role="tab"
                                   data-toggle="tab">
                                    {{ $lang }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="tab-content @if($hasLocales)padder-v @endif">
                        @forelse($locales as $code => $lang)
                        <div class="tab-pane @if($loop->first) active @endif" id="local-{{$code}}"
                             role="tabpanel">
                            {!! generate_form($type->fields(), $resource->toArray(), $code, $type->prefix) !!}
                        </div>
                        @empty
                        {!! generate_form($type->fields(), $resource->toArray(), null, $type->prefix) !!}
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!-- /column -->
        @endif
        <!-- column -->
        <div class="hbox-col wi-col">
            <div class="vbox">
                <div class="row-row">
                    <div data-controller="screen--tabs"
                         data-screen--tabs-slug="entyti-tab"
                         class="wrapper">
                        @if($hasOptions = ($type->options() !== []))
                        <div class="nav-tabs-alt">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active "
                                       data-action="screen--tabs#setActiveTab"
                                       data-target="#tab-main"
                                       id="button-tab-main"
                                       role="tab"
                                       data-toggle="tab">
                                        {!! __('Main') !!}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       data-action="screen--tabs#setActiveTab"
                                       data-target="#tab-options"
                                       id="button-tab-options"
                                       role="tab"
                                       data-toggle="tab">
                                        {!! __('Options') !!}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tab-main">
                                <div @if($hasOptions)class="padder-v"@endif>
                                    {!! generate_form($type->main(), $resource->toArray()) !!}
                                </div>
                            </div>
                            @if($hasOptions)
                            <div role="tabpanel" class="tab-pane" id="tab-options">
                                <div class="padder-v">
                                    {!! generate_form($type->options(), $resource->toArray(), null, 'options') !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /column -->
    </div>
</div>
