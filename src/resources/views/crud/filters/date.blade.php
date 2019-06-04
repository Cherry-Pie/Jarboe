
<label class="input smart-form">
    <i class="icon-append fa fa-calendar"></i>
    <input type="text"
           placeholder="{{ $filter->field()->getPlaceholder() }}"
           class="datepicker-s-{{ $filter->field()->name() }} form-control"
           autocomplete="off">
    <input type="hidden" name="search[{{ $filter->field()->name() }}]" value="{{ $filter->value() }}" class="datepicker-s-{{ $filter->field()->name() }}-value-field">
</label>

@push('scripts')
    <script>
        $(function () {
            var datepicker = $(".datepicker-s-{{ $filter->field()->name() }}").datepicker({
                dateFormat: "{{ $filter->field()->getDateFormat() }}",
                {{--numberOfMonths: {{ $filter->field()->getMonths() }},--}}
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                altField: '.datepicker-s-{{ $filter->field()->name() }}-value-field',
                altFormat: "YYYY-MM-DD",
            });
            @if ($filter->value())
                datepicker.datepicker('setDate', getDate("{{ $filter->value() }}", "YYYY-MM-DD"));
            @endif

            function getDate(value, format) {
                console.log(value);
                try {
                    return $.datepicker.parseDate(format, value);
                } catch (error) {}

                return null;
            }
        });
    </script>
@endpush
