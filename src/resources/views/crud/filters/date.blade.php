
<label class="input smart-form">
    <i class="icon-append fa fa-calendar"></i>
    <input type="text"
           name="search[{{ $filter->field()->name() }}]"
           value="{{ $filter->value() }}"
           placeholder="{{ $filter->field()->getPlaceholder() }}"
           class="datepicker-{{ $filter->field()->name() }} form-control"
           autocomplete="off">
</label>

@push('scripts')
    <script>
        $(".datepicker-{{ $filter->field()->name() }}").datepicker({
            dateFormat: "{{ $filter->field()->getDateFormat() }}",
            numberOfMonths: {{ $filter->field()->getMonths() }},
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
        });
    </script>
@endpush
