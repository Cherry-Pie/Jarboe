
<label class="label">{{ $field->title() }}</label>

<label style="display:inline;">
    <div class="input input-file {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        <span class="button">
            <input type="hidden" name="{{ $field->name() }}">
            <input type="file" name="{{ $field->name() }}{{ $field->isMultiple() ? '[]' : '' }}" {{ $field->isMultiple() ? 'multiple' : '' }} onchange="$(this).parent().next().val(this.value)">
            Browse
        </span>
        <input type="text" placeholder="{{ $field->getPlaceholder() }}" readonly="readonly">
    </div>


    @foreach ($errors->get($field->name()) as $message)
        <div class="note note-error">{{ $message }}</div>
    @endforeach
</label>