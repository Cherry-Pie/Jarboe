<div class="datepicker-range-container">
    <div class="pull-left">
        <div class="pull-left">
            <label class="input smart-form">
                <i class="icon-append fa fa-calendar"></i>
                <input type="text"
                       placeholder="{{ $filter->field()->getPlaceholder() }}"
                       class="datepicker-{{ $filter->field()->name() }}-range-from form-control"
                       autocomplete="off">
            </label>
        </div>
        <div class="pull-left input-group">
            <span class="input-group-addon">to</span>
        </div>
        <div class="pull-left">
            <label class="input smart-form">
                <i class="icon-append fa fa-calendar"></i>
                <input type="text"
                       placeholder="{{ $filter->field()->getPlaceholder() }}"
                       class="datepicker-{{ $filter->field()->name() }}-range-to form-control"
                       autocomplete="off">
            </label>
        </div>
    </div>
    <input type="hidden" name="search[{{ $filter->field()->name() }}][from]" value="{{ $filter->value()['from'] ?? '' }}" class="datepicker-s-{{ $filter->field()->name() }}-from-value-field">
    <input type="hidden" name="search[{{ $filter->field()->name() }}][to]" value="{{ $filter->value()['to'] ?? '' }}" class="datepicker-s-{{ $filter->field()->name() }}-to-value-field">
    <div class="clearfix"></div>
</div>

@push('scripts')
    <script>
        $(function () {
            var from = $(".datepicker-{{ $filter->field()->name() }}-range-from")
                .datepicker({
                    dateFormat: "{{ $filter->field()->getDateFormat() }}",
                    numberOfMonths: {{ $filter->field()->getMonths() }},
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                    altField: '.datepicker-s-{{ $filter->field()->name() }}-from-value-field',
                    altFormat: "YYYY-MM-DD",
                }).on("change", function () {
                    to.datepicker("option", "minDate", getDate(this.value, "{{ $filter->field()->getDateFormat() }}"));
                    $('.datepicker-s-{{ $filter->field()->name() }}-to-value-field, .datepicker-s-{{ $filter->field()->name() }}-from-value-field').trigger('change');
                });
            @if ($filter->value()['from'] ?? false)
                from.datepicker('setDate', getDate("{{ $filter->value()['from'] ?? '' }}", "YYYY-MM-DD"));
            @endif

            var to = $(".datepicker-{{ $filter->field()->name() }}-range-to")
                .datepicker({
                    dateFormat: "{{ $filter->field()->getDateFormat() }}",
                    numberOfMonths: {{ $filter->field()->getMonths() }},
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                    altField: '.datepicker-s-{{ $filter->field()->name() }}-to-value-field',
                    altFormat: "YYYY-MM-DD",
                }).on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this.value, "{{ $filter->field()->getDateFormat() }}"));
                    $('.datepicker-s-{{ $filter->field()->name() }}-to-value-field, .datepicker-s-{{ $filter->field()->name() }}-from-value-field').trigger('change');
                });
            @if ($filter->value()['to'] ?? false)
                to.datepicker('setDate', getDate("{{ $filter->value()['to'] ?? '' }}", "YYYY-MM-DD"));
            @endif

            function getDate(value, format) {
                try {
                    return $.datepicker.parseDate(format, value);
                } catch (error) {}

                return null;
            }

            $('.datepicker-s-{{ $filter->field()->name() }}-to-value-field, .datepicker-s-{{ $filter->field()->name() }}-from-value-field').on('change', function () {
                if (this.value.toLowerCase() == 'invalid date') {
                    this.value = '';
                }
            });
        });
    </script>
@endpush

@pushonce('style_files', <style>
    .datepicker-range-container .clearfix:after {
        content: ".";
        display: block;
        clear: both;
        visibility: hidden;
        line-height: 0;
        height: 0;
    }
    .datepicker-range-container > div {
        display: flex;
    }
    .datepicker-range-container > div > .input-group {
        height: 32px;
    }
    .datepicker-range-container > div > .input-group > .input-group-addon {
        width: 100%;
    }
</style>)
