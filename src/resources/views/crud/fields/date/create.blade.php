
<label class="label">{{ $field->title() }}</label>

<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <i class="icon-append fa fa-calendar"></i>
    <input type="hidden"
           name="{{ $field->name() }}"
           value="{{ $field->oldOrDefault() }}"
           class="datepicker-{{ $field->name() }}-value-field">
    <input type="text"
           value="{{ $field->oldOrDefault() }}"
           placeholder="{{ $field->getPlaceholder() }}"
           class="datepicker-{{ $field->name() }}"
           autocomplete="off">
</label>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach



@push('scripts')
    <script>
        $(".datepicker-{{ $field->name() }}").datepicker({
            dateFormat: "{{ $field->getDateFormat() }}",
            @if($field->getDefault())
            defaultDate: "{{ $field->getDefault() }}",
            @endif
            numberOfMonths: {{ $field->getMonths() }},
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
        }).on('change', function() {
            var d = $(this).datepicker('getDate');
            var date = [
                d.getFullYear(),
                ('0' + (d.getMonth() + 1)).slice(-2),
                ('0' + d.getDate()).slice(-2)
            ].join('-');
            $('.datepicker-{{ $field->name() }}-value-field').val(date);
        });
    </script>
@endpush

@push('scripts')
    <script>
        var $ctx = $('.datepicker-{{ $field->name() }}');
        if ($ctx.val()) {
            $ctx.val(moment($ctx.val()).format('{{ $field->getDateFormat() }}'));
        }
    </script>
@endpush
