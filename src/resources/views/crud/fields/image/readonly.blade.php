<?php
/** @var \Yaro\Jarboe\Table\Fields\Image $field */
/** @var \Yaro\Jarboe\Pack\Image $image */
?>
<label class="label">{{ $field->title() }}</label>
<div class="input-block">

    @foreach ($field->getImagesPack($model) as $index => $image)
        <div class="image-block">
            <div class="image-left-part" style="position: relative;">
                <div class="input input-file state-disabled {{ $errors->has($field->name()) ? 'state-error' : '' }}">
                    <span class="button disabled">
                        {{ __('jarboe::fields.image.browse') }}
                    </span>
                    <input type="text"
                           disabled
                           class="upload-input">
                </div>

                <div class="image-row-container-preview" style="background-color: {{ $image->cropRotateBackground() }};">
                    <img src="{{ $image->croppedOrOriginalSourceUrl('/vendor/jarboe/img/placeholder.png') }}" {{ $image->croppedOrOriginalSourceUrl() ? 'data-lity' : '' }}>
                </div>
            </div>

            @foreach ($errors->get($field->name()) as $message)
                <div class="note note-error">{{ $message }}</div>
            @endforeach

        </div>
    @endforeach

</div>
