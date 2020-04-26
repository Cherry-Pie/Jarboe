<?php
/** @var \Yaro\Jarboe\Table\Fields\Number $field */
?>
@if ($field->hasClipboardButton() && (string) $field->getAttribute($model) !== '')
    <div class="p-relative">
        <a href="javascript:void(0);" class="btn btn-labeled btn-default clipclip" data-clipboard-text="{{ $field->getAttribute($model) }}" id="clipclip-number-{{ $field->name() }}-{{ $model->getKey() }}">
            {{ $field->getClipboardCaption($model) ?: __('jarboe::fields.clipboard_copy') }}
        </a>
    </div>
@endif

<span id="xeditable-number-{{ $field->name() }}-{{ $model->getKey() }}">{{ $field->getAttribute($model) }}</span>

@if ($field->isInline())
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/jquery.mockjax.min.js"></script>)
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/x-editable.min.js"></script>)

    @push('scripts')
        <script>
            $('#xeditable-number-{{ $field->name() }}-{{ $model->getKey() }}').editable({
                url: '{{ $field->getInlineUrl() }}',
                type: 'number',
                @if ($field->hasMin())
                    min: '{{ $field->getMin() }}',
                @endif
                @if ($field->hasMax())
                    max: '{{ $field->getMax() }}',
                @endif
                step: '{{ $field->getStep() }}',
                pk: '{{ $model->getKey() }}',
                name: '{{ $field->name() }}',
                title: '{{ $field->title() }}',
                placeholder: "{{ $field->getPlaceholder() }}",
                params: function(params) {
                    var data = {};
                    data['_pk'] = params.pk;
                    data['_name'] = params.name;
                    data['_value'] = params.value;
                    data[params.name] = params.value;

                    return data;
                },
                success: function(response, newValue) {
                    $('#clipclip-number-{{ $field->name() }}-{{ $model->getKey() }}').attr('data-clipboard-text',  response.value);
                },
                error: function(response, newValue) {
                    return response.responseJSON.errors["{{ $field->name() }}"].join("\n");
                },
                @foreach ($field->getInlineOptions() as $key => $value)
                    {{ $key }}: "{{ $value }}",
                @endforeach
            });
        </script>
    @endpush
@endif
