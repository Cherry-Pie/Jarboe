
<label class="label">{{ $field->title() }}</label>

<label class="input input-file state-disabled">
    <span class="button disabled">
        {{ __('jarboe::fields.image.browse') }}
    </span>
    <input type="text" disabled="disabled" accept="image/*">
</label>

@include('jarboe::crud.fields.image_deprecated.inc.images_box')