<?php
/** @var \Yaro\Jarboe\Table\Fields\ColorPicker $field */
?>
<label class="label">{{ $field->title() }}</label>
<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <i class="icon-append icon-color" style="background-color: {{ $field->oldOrDefault() ?: '#fff' }};">&nbsp;&nbsp;&nbsp;&nbsp;</i>
    <input class="colorpicker-field"
           type="text"
           value="{{ $field->oldOrDefault() }}"
           name="{{ $field->name() }}"
           data-color-format="{{ $field->getType() }}"
           placeholder="{{ $field->getPlaceholder() }}">
</label>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/colorpicker/bootstrap-colorpicker.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('input.colorpicker-field')
                .colorpicker()
                .on('changeColor', function(event) {
                    let color = event.color.toRGB();
                    color = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ',' + color.a + ')';
                    $(this).parent().find('.icon-color').css('background-color', color);
                });
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>
@endpush
