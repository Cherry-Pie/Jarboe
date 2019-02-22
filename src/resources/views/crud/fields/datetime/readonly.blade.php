
<label class="label">{{ $field->title() }}</label>

<div class="input state-disabled">
    <input class="form-control datetimepicker-{{ $field->name() }}" type="text" placeholder="{{ $field->getPlaceholder() }}" value="{{ $model->{$field->name()} }}" disabled="disabled">
    <i class="icon-append fa fa-calendar"></i>
</div>


@push('scripts')
    <script>
        var $ctx = $('.datetimepicker-{{ $field->name() }}');
        if ($ctx.val()) {
            $ctx.val(moment($ctx.val()).format('{{ $field->getDateFormat() }}'));
        }
    </script>
@endpush
