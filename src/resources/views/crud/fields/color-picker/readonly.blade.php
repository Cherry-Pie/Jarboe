<?php
/** @var \Yaro\Jarboe\Table\Fields\ColorPicker $field */
?>
<label class="label">{{ $field->title() }}</label>
<label class="input state-disabled {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <i class="icon-append icon-color" style="background-color: {{ $field->getAttribute($model) ?: '#fff' }};">&nbsp;&nbsp;&nbsp;&nbsp;</i>
    <input type="text"
           value="{{ $field->getAttribute($model) }}"
           data-color-format="{{ $field->getType() }}"
           placeholder="{{ $field->getPlaceholder() }}"
           disabled="disabled">
</label>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach
