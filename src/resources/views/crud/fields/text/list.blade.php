@if ($field->hasClipboardButton() && (string) $field->getAttribute($model, $locale ?? null) !== '')
    <div class="p-relative">
        <a href="javascript:void(0);"
           class="btn btn-labeled btn-default clipclip"
           data-clipboard-text="{{ $field->getAttribute($model, $locale ?? null) }}"
           id="clipclip-text-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}">
            {{ $field->getClipboardCaption($model) ?: __('jarboe::fields.clipboard_copy') }}
        </a>
    </div>
@endif

<span id="xeditable-text-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}">{{ $field->getAttribute($model, $locale ?? null) }}</span>


@if ($field->isInline())
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/jquery.mockjax.min.js"></script>)
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/x-editable.min.js"></script>)

    @push('scripts')
        <script>
            $('#xeditable-text-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}').editable({
                url: '{{ $field->getInlineUrl() }}',
                type: 'text',
                pk: '{{ $model->getKey() }}',
                name: '{{ $field->name() }}',
                title: '{{ $field->title() }}',
                placeholder: "{{ $field->getPlaceholder() }}",
                tpl: '<input {!! $field->hasMaxlength() ? 'maxlength="'.$field->getMaxlength().'"' : '' !!} type="text">',
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
                    $('#clipclip-text-{{ $field->name() }}-{{ $model->getKey() }}-{{ $locale ?? 'default' }}').attr('data-clipboard-text',  response.value);
                },
                error: function(response, newValue) {
                    return response.responseJSON.errors["{{ $field->name() }}"].join("\n");
                },
                @foreach ($field->getInlineOptions() as $key => $value)
                    {{ $key }}: "{{ $value }}",
                @endforeach
            }).on('shown', function(e, editable) {
                @if ($field->isMaskable())
                    editable.input.$input.mask('{{ $field->getMaskPattern() }}', { placeholder: '{{ $field->getMaskPlaceholder() }}' });
                @endif
            });
        </script>
    @endpush
@endif
