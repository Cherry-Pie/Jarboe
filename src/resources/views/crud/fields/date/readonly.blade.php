
<label class="label">{{ $field->title() }}</label>

<label class="input state-disabled">
    <i class="icon-append fa fa-calendar"></i>
    <input type="text" class="datepicker-{{ $field->name() }}" value="{{ $model->{$field->name()} }}" placeholder="{{ $field->getPlaceholder() }}" disabled="disabled">
</label>


@push('scripts')
    <script>
        var $ctx = $('.datepicker-{{ $field->name() }}');
        if ($ctx.val()) {
            $ctx.val(moment($ctx.val()).format('{{ $field->getDateFormat() }}'));
        }
    </script>
@endpush
