@component($typeForm, get_defined_vars())
    <div data-controller="fields--tag"
         data-fields--tag-url="{{ rtrim(route($searchRoute), '/') . '/' }}">
        <select @attributes($attributes)>
            @foreach($options as $tag)
                <option value="{{ $tag['slug'] ?? $tag }}" selected="selected">
                    {{ $tag['name'] ?? $tag }}
                </option>
            @endforeach
        </select>
    </div>
@endcomponent
