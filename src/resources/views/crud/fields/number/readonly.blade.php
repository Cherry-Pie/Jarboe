<?php
/** @var \Yaro\Jarboe\Table\Fields\Number $field */
?>
<label class="label">{{ $field->title() }}</label>
<label class="input state-disabled">
    @if ($field->hasTooltip())
        <i class="icon-append fa fa-question-circle"></i>
    @endif

    <input type="number" value="{{ $model->{$field->name()} }}" disabled="disabled" placeholder="{{ $field->getPlaceholder() }}">

    @if ($field->hasTooltip())
        <b class="tooltip tooltip-top-right">
            <i class="fa fa-warning txt-color-teal"></i>
            {{ $field->getTooltip() }}
        </b>
    @endif
</label>
