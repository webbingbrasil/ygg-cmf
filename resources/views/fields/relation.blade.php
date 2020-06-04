@component($typeForm, get_defined_vars())
    <div @empty(!$action)class="input-group"@endempty >
        <div @empty(!$action)style="flex: 1 1 0%;"@endempty data-controller="fields--relation"
             data-fields--relation-id="{{$id}}"
             data-fields--relation-placeholder="{{$attributes['placeholder'] ?? ''}}"
             data-fields--relation-value="{{  $value }}"
             data-fields--relation-model="{{ $relationModel }}"
             data-fields--relation-name="{{ $relationName }}"
             data-fields--relation-key="{{ $relationKey }}"
             data-fields--relation-scope="{{ $relationScope }}"
             data-fields--relation-search-scope="{{ $relationSearchScope }}"
             data-fields--relation-append="{{ $relationAppend }}"
             data-fields--relation-route="{{ route($searchRoute) }}"
        >
            <select id="{{$id}}" class="form-control" data-target="fields--relation.select" @attributes($attributes)>
            </select>
        </div>
        @empty(!$action)
            {!! $action->render() !!}
        @endempty
    </div>
@endcomponent
