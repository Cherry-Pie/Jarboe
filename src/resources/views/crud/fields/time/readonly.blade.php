
<label class="label">{{ $field->title() }}</label>

<label class="input state-disabled">
    <i class="icon-append fa fa-clock-o"></i>
    <input type="text" value="{{ $model->{$field->name()} }}" placeholder="{{ $field->getPlaceholder() }}" disabled="disabled">
</label>
