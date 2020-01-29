<div class="wrapper v-center">
    <div class="dropdown col no-padder">
        <a href="#" class="nav-link p-0 v-center" data-toggle="dropdown">
                    <span class="thumb-sm avatar mr-3">
                        <img src="https://api.adorable.io/avatars/40/abott@adorable.png" class="b b-dark bg-light">
                    </span>
            <span style="width:11em;font-size: 0.85em;">
                <span class="text-ellipsis">User Title</span>
                <span class="text-muted d-block text-ellipsis">User Sub title</span>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow bg-white">

            {!! Dashboard::menu()->render('Profile','platform::partials.dropdownMenu') !!}

            @if(Dashboard::menu()->container->where('location','Profile')->isNotEmpty())
                <div class="dropdown-divider"></div>
            @endif

            <a href="system" class="dropdown-item">
                <i class="icon-settings mr-2" aria-hidden="true"></i>
                <span>{{ __('Systems') }}</span>
            </a>

            <a href="logout"
               class="dropdown-item"
               data-controller="layouts--form"
               data-action="layouts--form#submitByForm"
               data-layouts--form-id="logout-form"
               dusk="logout-button">
                <i class="icon-logout mr-2" aria-hidden="true"></i>
                <span>{{ __('Sign out') }}</span>
            </a>
            <form id="logout-form"
                  class="hidden"
                  action="{logout"
                  method="POST"
                  data-controller="layouts--form"
                  data-action="layouts--form#submit"
            >
                @csrf
            </form>

        </div>
    </div>

</div>
