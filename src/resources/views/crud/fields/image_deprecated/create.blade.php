<label class="label">{{ $field->title() }}</label>

<div class="input input-file {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <span class="button">
        <input type="file"
               name="{{ $field->name() }}{{ $field->isMultiple() ? '[]' : '' }}"
               {{ $field->isMultiple() ? 'multiple' : '' }}
               onchange="onChange{{ $field->name() }}(this)"
               accept="image/*">
        {{ __('jarboe::fields.image.browse') }}
    </span>
    <input type="text"
           placeholder="{{ $field->getPlaceholder() }}"
           readonly="readonly">
</div>

@include('jarboe::crud.fields.image_deprecated.inc.images_box')

@include('jarboe::crud.fields.image_deprecated.inc.error_messages')

@include('jarboe::crud.fields.image_deprecated.inc.image_preview')
