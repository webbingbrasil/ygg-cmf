@if($filters->count() > 0)
    <div class="row justify-content-start" data-controller="screen--filter">
        @foreach($filters->where('display', true) as $filter)
            <div class="{{$filter->col}} align-self-start">
                {!! $filter->build() !!}
            </div>
        @endforeach
        @if($displayFormButtons)
            <div class="col-sm-auto ml-auto align-self-end text-right">
                <div class="form-group">
                    <div class="btn-group" role="group">
                        <button
                                data-action="screen--filter#clear"
                                class="btn btn-default">
                            <i class="icon-refresh"></i>
                        </button>
                        <button type="submit"
                                form="filters"
                                class="btn btn-default">
                            <i class="icon-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
