<label class="label">{{ $field->title() }}</label>

<label class="input state-disabled">
    <i class="icon-append fa fa-clock-o"></i>
    <input type="text" value="{{ $field->getAttribute($model) }}" placeholder="{{ $field->getPlaceholder() }}" disabled="disabled">
</label>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach
