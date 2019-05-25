<div class="datepicker-range-container">
    <div class="pull-left">
        <div class="pull-left">
            <label class="input smart-form">
                <i class="icon-append fa fa-calendar"></i>
                <input type="text"
                       name="search[{{ $filter->field()->name() }}][from]"
                       value="{{ $filter->value()['from'] ?? '' }}"
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
                       name="search[{{ $filter->field()->name() }}][to]"
                       value="{{ $filter->value()['to'] ?? '' }}"
                       placeholder="{{ $filter->field()->getPlaceholder() }}"
                       class="datepicker-{{ $filter->field()->name() }}-range-to form-control"
                       autocomplete="off">
            </label>
        </div>
    </div>
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
                }).on("change", function () {
                    to.datepicker("option", "minDate", getDate(this));
                });
            var to = $(".datepicker-{{ $filter->field()->name() }}-range-to")
                .datepicker({
                    dateFormat: "{{ $filter->field()->getDateFormat() }}",
                    numberOfMonths: {{ $filter->field()->getMonths() }},
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                }).on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this));
                });

            function getDate(element) {
                try {
                    return $.datepicker.parseDate("{{ $filter->field()->getDateFormat() }}", element.value);
                } catch (error) {}

                return null;
            }
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
