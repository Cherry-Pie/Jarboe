
<div class="inline-group">
    <label class="label">&nbsp;</label>
    <label class="checkbox state-disabled">
        <input type="checkbox"
               disabled="disabled"
               @if ($model->{$field->name()})
               checked="checked"
               @endif
        >
        <i></i>{{ $field->title() }}</label>
</div>
