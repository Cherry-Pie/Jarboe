<?php
/** @var \Yaro\Jarboe\Table\Fields\Image $field */
/** @var \Yaro\Jarboe\Pack\Image $image */
?>
<div class="image-block" data-should-auto-open-modal="{{ $field->shouldAutoOpenModal() }}">
    <div class="image-left-part">
        <div class="input input-file {{ $errors->has($field->name() .'.'. $index .'.*') ? 'state-error' : '' }}">
                <span class="button">
                    <input type="file"
                           class="upload-input"
                           name="{{ $field->name() }}[{{ $index }}][file]"
                           accept="image/*">
                    {{ __('jarboe::fields.image.browse') }}
                </span>
            <input type="text"
                   placeholder="{{ $field->getPlaceholder() }}"
                   readonly="readonly">
        </div>

        <div class="image-row-container-preview" style="background-color: {{ $image->cropRotateBackground() }};">
            <img src="{{ $image->croppedOrOriginalSourceUrl('/vendor/jarboe/img/placeholder.png') }}">
        </div>
        <a href="javascript:void(0);"
           class="btn btn-default cropper-modal-open {{ $image->croppedOrOriginalSourceUrl() ? '' : 'disabled' }}"
        >{{ __('jarboe::fields.image.open_modal') }}</a>
        <a href="javascript:void(0);"
           class="btn btn-danger image-remove {{ $image->croppedOrOriginalSourceUrl() ? '' : 'disabled' }}"
        >{{ __('jarboe::fields.image.delete_image') }}</a>


        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][sources][original]" class="original-source" value="{{ $image->originalSource() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][sources][cropped]" class="cropped-source" value="{{ $image->croppedSource() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][crop][width]" class="crop-width" value="{{ $image->cropWidth() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][crop][height]" class="crop-height" value="{{ $image->cropHeight() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][crop][x]" class="crop-x" value="{{ $image->cropX() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][crop][y]" class="crop-y" value="{{ $image->cropY() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][crop][rotate]" class="crop-rotate" value="{{ $image->cropRotate() }}">
        <input type="hidden" name="{{ $field->name() }}[{{ $index }}][crop][rotate_background]" class="crop-rotate-background" value="{{ $image->cropRotateBackground() }}">
    </div>

    @foreach ($errors->get($field->name() .'.'. $index .'.*') as $errorKey => $errorMessages)
        @foreach ($errorMessages as $message)
            <div class="note note-error">{{ $message }}</div>
        @endforeach
    @endforeach

    @include('jarboe::crud.fields.image.inc.modal')
</div>
