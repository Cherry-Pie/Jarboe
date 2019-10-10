
<label class="label">{{ $field->title() }}</label>

<label class="input input-file state-disabled">
    <span class="button disabled">
        Browse
    </span>
    <input type="text" disabled="disabled">
</label>


@include('jarboe::crud.fields.file.inc.urls', [
    'field' => $field,
    'model' => $model,
])
