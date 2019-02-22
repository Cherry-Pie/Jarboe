
@if ($field->hasClipboardButton() && (string) $field->getAttribute($model, $locale ?? null) !== '')
    <div class="p-relative">
        <a href="javascript:void(0);"
           class="btn btn-labeled btn-default clipclip"
           data-clipboard-text="{{ $field->getAttribute($model, $locale ?? null) }}"
           id="clipclip-textarea-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}">
            {{ $field->getClipboardCaption($model) ?: __('jarboe::fields.clipboard_copy') }}
        </a>
    </div>
@endif

<span id="xeditable-textarea-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}" style="white-space: pre-wrap;">{{ $field->getAttribute($model, $locale ?? null) }}</span>

@if ($field->isInline())
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/jquery.mockjax.min.js"></script>)
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/x-editable.min.js"></script>)

    @push('scripts')
        <script>
            $('#xeditable-textarea-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}').editable({
                url: '{{ $field->getInlineUrl() }}',
                type: 'textarea',
                pk: '{{ $model->getKey() }}',
                name: '{{ $field->name() }}',
                title: '{{ $field->title() }}',
                placeholder: "{{ $field->getPlaceholder() }}",
                rows: {{ $field->getRowsNum() }},
                params: function(params) {
                    var data = {};
                    data['_pk'] = params.pk;
                    data['_name'] = params.name;
                    data['_value'] = params.value;
                    data['_locale'] = '{{ $locale ?? '' }}';

                    @if (isset($locale))
                        data[params.name] = {
                            {{ $locale }}: params.value
                        };
                    @else
                        data[params.name] = params.value;
                    @endif

                    return data;
                },
                success: function(response, newValue) {
                    $('#clipclip-textarea-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}').attr('data-clipboard-text',  response.value);
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
