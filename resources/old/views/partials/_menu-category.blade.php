@if($menuItem->type === 'category')
    <ygg-collapsible-item label="{{ $menuItem->label }}">
        @foreach($menuItem->resources as $resource)
            @if($menuItem->type === 'category')
                @include('ygg::partials._menu-category', [
                    'menuItem' => $resource,
                ])
            @else
                @include('ygg::partials._menu-item', [
                    'item' => $resource,
                    'isCurrent' => $resource->isCategory() ? false : $yggMenu->currentResource === $resource->key
                ])
            @endif
        @endforeach
    </ygg-collapsible-item>
@else
    @include('ygg::partials._menu-item', [
        'item' => $menuItem,
        'isCurrent' => $yggMenu->currentResource === $menuItem->key
    ])
@endif