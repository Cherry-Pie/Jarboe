<div class="inline-group">
    <label class="label">{{ $field->title() }}</label>
    <label class="checkbox state-disabled {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        <input type="checkbox"
               disabled="disabled"
               @if ($field->getAttribute($model))
               checked="checked"
               @endif
        >
        <i></i>{{ $field->title() }}</label>
</div>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach
