<ygg-left-nav v-cloak
    current="{{ $yggMenu->currentResource }}"
    title="{{ $yggMenu->name }}"
    :items="{{ json_encode($yggMenu->menuItems) }}"
    :has-global-filters="{{ json_encode($hasGlobalFilters) }}"
>
    <ul role="menubar" class="YggLeftNav__list" aria-hidden="false" v-cloak>
        <ygg-nav-item disabled>
            <span title="{{ $yggMenu->user }}">
                {{ $yggMenu->user }}
            </span>
            <a href="{{ route('ygg.logout') }}"> <ygg-item-visual :item="{ icon:'fa-sign-out' }" icon-class="fa-fw"></ygg-item-visual></a>
        </ygg-nav-item>

        @foreach($yggMenu->menuItems as $menuItem)
            @if($menuItem->type === 'category')
                <ygg-collapsible-item label="{{ $menuItem->label }}">
                    @foreach($menuItem->resources as $resource)
                        @include('ygg::partials._menu-item', [
                            'item' => $resource,
                            'isCurrent' => $yggMenu->currentResource === $resource->key
                        ])
                    @endforeach
                </ygg-collapsible-item>
            @else
                @include('ygg::partials._menu-item', [
                    'item' => $menuItem,
                    'isCurrent' => $yggMenu->currentResource === $menuItem->key
                ])
            @endif
        @endforeach
    </ul>
</ygg-left-nav>