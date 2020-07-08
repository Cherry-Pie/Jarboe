<?php
/** @var \Yaro\Jarboe\Etc\CustomFields\OtpSecret $field */
?>

<label class="label">
    {{ $field->title() }}
</label>

<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    @if ($field->hasTooltip())
        <i class="icon-append fa fa-question-circle"></i>
    @endif

    <input type="text"
           value="{{ $field->oldOrAttribute($model) }}"
           name="{{ $field->name() }}"
           readonly
           placeholder="{{ $field->getPlaceholder() }}">

    @if ($field->hasTooltip())
        <b class="tooltip tooltip-top-right">
            <i class="fa fa-warning txt-color-teal"></i>
            {{ $field->getTooltip() }}
        </b>
    @endif

    @foreach ($errors->get($field->name()) as $message)
        <div class="note note-error">{{ $message }}</div>
    @endforeach

    <div style="text-align: center;">
        {!! $field->qrSvg($model) !!}
    </div>
</label>
