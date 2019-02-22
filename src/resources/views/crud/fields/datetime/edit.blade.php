
<label class="label">{{ $field->title() }}</label>

<div class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <input type="hidden"
           name="{{ $field->name() }}"
           value="{{ $field->oldOrAttribute($model) }}"
           class="datetimepicker-{{ $field->name() }}-value-field">
    <input class="form-control datetimepicker-{{ $field->name() }}"
           type="text"
           placeholder="{{ $field->getPlaceholder() }}"
           value="{{ $field->oldOrAttribute($model) }}">
    <i class="icon-append fa fa-calendar"></i>
</div>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@push('styles')
    <style>
        .bootstrap-datetimepicker-widget.dropdown-menu {
            padding: 10px 28px;
        }
    </style>
@endpush

@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>)

@push('scripts')
    <script>
        $('.datetimepicker-{{ $field->name() }}').datetimepicker({
            format: '{{ $field->getDateFormat() }}',
            sideBySide: true
        }).on('dp.change', function(e) {
            console.log(e.date.format('YYYY-MM-DD HH:mm:ss'));
            $('.datetimepicker-{{ $field->name() }}-value-field').val(e.date.format('YYYY-MM-DD HH:mm:ss'));
        });
    </script>
@endpush

@push('scripts')
    <script>
        var $ctx = $('.datetimepicker-{{ $field->name() }}');
        if ($ctx.val()) {
            $ctx.val(moment($ctx.val()).format('{{ $field->getDateFormat() }}'));
        }
    </script>
@endpush
