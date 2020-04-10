@section('flash_notification.sub_message')
    @if(session('restore'))
        <a href="#"
           data-controller="layouts--form"
           data-action="layouts--form#submitByForm"
           data-layouts--form-id="restore-resource-form"
        >
            {{__('Restore the record.')}}
        </a>

        <form id="restore-resource-form"
              class="hidden"
              action="{{ session('restore') }}"
              data-controller="layouts--form"
              data-action="layouts--form#submit"
              method="POST"
        >
            @csrf
        </form>
    @endif
@stop