<div
    data-controller="screen--chart"
    data-screen--chart-parent="#{{$slug}}"
    data-screen--chart-title="{{$title}}"
    data-screen--chart-labels="{{$labels}}"
    data-screen--chart-datasets="{{$data}}"
    data-screen--chart-type="{{$type}}"
    data-screen--chart-height="{{$height}}"
    data-screen--chart-colors="{{$colors}}"
    data-screen--chart-axis-options="{{$axisOptions}}"
    data-screen--chart-bar-options="{{$barOptions}}"
    data-screen--chart-line-options="{{$lineOptions}}"
    data-screen--chart-markers="{{$markers}}"
    data-screen--chart-regions="{{$regions}}"
    data-screen--chart-format-tooltip-x="{{$formatTooltipX}}"
    data-screen--chart-format-tooltip-y="{{$formatTooltipY}}"
>
    <div class="row padder-v">
        <div class="pos-rlt w-full">
            <div class="top-right pt-1 pr-4"  style="z-index: 1">
                <button class="btn btn-sm btn-link"
                        data-action="screen--chart#export">
                    {{ __('Export') }}
                </button>
            </div>

            <figure id="{{$slug}}" class="w-full h-full"></figure>
        </div>
    </div>
</div>
