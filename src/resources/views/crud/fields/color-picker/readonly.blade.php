
<label class="label">{{ $field->title() }}</label>
<label class="input state-disabled">
    <i class="icon-append icon-color colorpicker-{{ $field->name() }}-icon"></i>
    <input class="colorpicker-{{ $field->name() }}" type="text" value="{{ $model->{$field->name()} }}" data-color-format="{{ $field->getType() }}" placeholder="{{ $field->getPlaceholder() }}" disabled="disabled">
</label>


@push('styles')
<style id="colorpicker-{{ $field->name() }}">
    .icon-color.colorpicker-{{ $field->name() }}-icon:before {
        content: "\00a0 \00a0 \00a0 \00a0 ";
        background-color: {{ $model->{$field->name()} ?: '#fff' }};
    }
</style>
@endpush