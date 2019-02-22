@if ($field->hasTooltip())
    <b class="tooltip tooltip-top-right">
        <i class="fa fa-warning txt-color-teal"></i>
        {{ $field->getTooltip() }}
    </b>
@endif