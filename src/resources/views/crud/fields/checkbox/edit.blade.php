
<div class="inline-group">
    <label class="label">&nbsp;</label>
    <label class="checkbox {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        <input type="checkbox"
               name="{{ $field->name() }}"
               @if ($field->oldOrAttribute($model))
               checked="checked"
               @endif
        >
        <i></i>{{ $field->title() }}</label>
</div>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach