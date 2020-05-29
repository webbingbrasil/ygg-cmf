<footer class="wrapper w-full">
    <div class="row v-md-center">
        <div class="col-sm-5">
            @if($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

                @if(isset($columns) && \Ygg\Screen\TD::isShowVisibleColumns($columns))
                    <div class="btn-group dropup d-inline-block">
                        <button type="button"
                                class="btn btn-sm btn-link dropdown-toggle p-0 m-0"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            {{ __('Configure columns') }}
                        </button>
                        <div class="dropdown-menu dropdown-column-menu">
                            @foreach($columns as $column)
                                {!! $column->buildItemMenu() !!}
                            @endforeach
                        </div>
                    </div>
                @endif

                <small class="text-muted block">
                    {{ __('Displayed records: :from-:to of :total',[
                        'from' => ($paginator->currentPage() -1 ) * $paginator->perPage() + 1,
                        'to' => ($paginator->currentPage() -1 ) * $paginator->perPage() + count($paginator->items()),
                        'total' => $paginator->total(),
                    ]) }}
                </small>
            @endif

        </div>
        <div class="col-sm-7 text-right text-center-xs">
            {!! $paginator->appends(request()->except(['page','_token']))->links('platform::partials.pagination') !!}
        </div>
    </div>
</footer>
