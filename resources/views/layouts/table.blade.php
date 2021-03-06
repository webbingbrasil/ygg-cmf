<div class="row"
     data-controller="layouts--table"
     data-layouts--table-slug="{{$slug}}"
>
    <div class="w-full table-responsive-lg">
        <div class="wrapper w-full">
            <div class="row v-md-center">
                <div class="col-md-12">
                    {!! $filters !!}
                </div>
            </div>
        </div>

        <table class="table bg-white">
            <thead>
            <tr>
                @foreach($columns as $column)
                    {!! $column->buildTh() !!}
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $source)
                <tr @attributes($rowAttributes($source))>
                    @foreach($columns as $column)
                        {!! $column->buildTd($source) !!}
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        @if($rows instanceof \Illuminate\Contracts\Pagination\Paginator && $rows->isEmpty())
            <div class="text-center bg-white pt-5 pb-5 w-full">
                <h3 class="font-thin">
                    <i class="{{ $iconNotFound }} block m-b"></i>
                    {!!  $textNotFound !!}
                </h3>

                {!! $subNotFound !!}
            </div>
        @endif

        @includeWhen($rows instanceof \Illuminate\Contracts\Pagination\Paginator && $rows->isNotEmpty(),
            'platform::layouts.pagination',[
                'paginator' => $rows,
                'columns' => $columns
            ]
          )
    </div>
</div>
