<label class="label">{{ $field->title() }}</label>

<label style="display:inline;">
    <div class="input input-file {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        <span class="button disabled">
            <input type="file" readonly disabled>
            {{ __('jarboe::fields.file.browse') }}
        </span>
        <input type="text" placeholder="{{ $field->getPlaceholder() }}" readonly disabled>
    </div>
</label>

@include('jarboe::crud.fields.file.inc.urls', [
    'field' => $field,
    'model' => $model,
])

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach
