@component($typeForm, get_defined_vars())
    <div data-controller="fields--cropper"
         data-fields--cropper-value="{{ $attributes['value'] }}"
         data-fields--cropper-storage="{{ $storage ?? 'public' }}"
         data-fields--cropper-width="{{ $width }}"
         data-fields--cropper-height="{{ $height }}"
         data-fields--cropper-min-width="{{ $minWidth }}"
         data-fields--cropper-min-height="{{ $minHeight }}"
         data-fields--cropper-max-width="{{ $maxWidth }}"
         data-fields--cropper-max-height="{{ $maxHeight }}"
         data-fields--cropper-target="{{ $target }}"
         data-fields--cropper-url="{{ $url }}"
         data-fields--cropper-max-file-size="{{ $maxFileSize }}"
    >
        <div class="border-dashed text-left p-3 cropper-actions">

            <div class="fields-cropper-container">
                <img width="{{ $width }}" height="{{ $height }}" src="#" class="cropper-preview img-fluid m-b-md border" alt="">
            </div>

            <span class="mt-1 float-left pr-3">{{ __('Upload image from your computer:') }}</span>

            <div class="btn-group">
                <label class="btn btn-default m-n">
                    <i class="icon-cloud-upload mr-2"></i> {{ __('Browse') }}
                    <input type="file"
                           accept="image/*"
                           data-target="fields--cropper.upload"
                           data-action="change->fields--cropper#upload click->fields--cropper#openModal"
                           class="d-none">
                </label>

                <button type="button" class="btn btn-outline-danger cropper-remove"
                        data-action="fields--cropper#clear">{{ __('Remove') }}</button>
            </div>

            <input type="file"
                   accept="image/*"
                   class="d-none">
        </div>

        <input class="cropper-path d-none"
               type="text"
               data-target="fields--cropper.source"
               @attributes($attributes)
        >

        <div class="modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        <div class="position-relative">
                            <img class="upload-panel">
                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-link"
                                    data-dismiss="modal">
                                {{ __('Close') }}
                            </button>

                            <button type="button"
                                    class="btn btn-default"
                                    data-action="fields--cropper#crop">
                                {{ __('Crop') }}
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endcomponent
