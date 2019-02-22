
<label class="label">{{ $field->title() }}</label>

<div class="input input-file {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <span class="button">
        <input type="file" name="{{ $field->name() }}{{ $field->isMultiple() ? '[]' : '' }}" {{ $field->isMultiple() ? 'multiple' : '' }} onchange="$(this).parent().next().val(this.value)">
        Browse
    </span>
    <input type="text" placeholder="{{ $field->getPlaceholder() }}" readonly="readonly">
</div>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach