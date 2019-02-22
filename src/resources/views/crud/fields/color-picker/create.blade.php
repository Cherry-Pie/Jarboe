
<label class="label">{{ $field->title() }}</label>
<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <i class="icon-append icon-color colorpicker-{{ $field->name() }}-icon"></i>
    <input class="colorpicker-{{ $field->name() }}" type="text" value="{{ $field->oldOrDefault() }}" name="{{ $field->name() }}" data-color-format="{{ $field->getType() }}" placeholder="{{ $field->getPlaceholder() }}">
</label>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


<style id="colorpicker-{{ $field->name() }}">
    .icon-color.colorpicker-{{ $field->name() }}-icon:before {
        content: "\00a0 \00a0 \00a0 \00a0 ";
        background-color: {{ $field->oldOrDefault() ?: '#fff' }};
    }
</style>

@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/colorpicker/bootstrap-colorpicker.min.js"></script>)

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".colorpicker-{{ $field->name() }}").colorpicker();

            $('.colorpicker-{{ $field->name() }}').blur(function () {
                $('#colorpicker-{{ $field->name() }}').remove();
                $('head').append('<style id="colorpicker-{{ $field->name() }}">.icon-color.colorpicker-{{ $field->name() }}-icon:before {content: "\\00a0 \\00a0 \\00a0 \\00a0 ";background-color: '+ $(this).val() +';}</style>');
            })
        })
    </script>
@endpush