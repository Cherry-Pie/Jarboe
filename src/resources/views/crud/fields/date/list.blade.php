
<span id="xeditable-number-{{ $field->name() }}-{{ $model->getKey() }}"
      style="display: none;"
      class="parse-to-format-moment"
      data-value="{{ $model->{$field->name()} }}"
      data-momentformat="{{ $field->getDateFormat() }}">{{ $model->{$field->name()} }}</span>

@pushonce('script_files', <script>
    $('.parse-to-format-moment').each(function() {
        var $ctx = $(this);
        if ($ctx.data('value')) {
            $ctx.text(moment($ctx.data('value')).format($ctx.data('momentformat')));
        }
        $ctx.show();
    });
</script>)

@if ($field->isInline())
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/jquery.mockjax.min.js"></script>)
    @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/x-editable/x-editable.min.js"></script>)

    @push('scripts')
        <script>
            $('#xeditable-number-{{ $field->name() }}-{{ $model->getKey() }}').editable({
                url: '{{ $field->getInlineUrl() }}',
                type: 'date',
                pk: '{{ $model->getKey() }}',
                name: '{{ $field->name() }}',
                title: '{{ $field->title() }}',
                value: '{{ $model->{$field->name()} }}',
                format: 'yyyy-mm-dd',
                viewformat: 'yyyy-mm-dd',
                clear: {!! $field->isNullable() ? '"× clear"' : 'false' !!},
                display: function(value) {
                    var $ctx = $(this);
                    if (value) {
                        var date = moment(value).format($ctx.data('momentformat'));
                        $ctx.text(date);
                        return date;
                    }
                    $ctx.addClass('editable-empty').text('');
                },
                params: function(params) {
                    var data = {};
                    data['_pk'] = params.pk;
                    data['_name'] = params.name;
                    data['_value'] = params.value;
                    data[params.name] = params.value;

                    return data;
                },
                success: function(response, newValue) {
                    console.log(newValue);
                    return {
                        newValue: response.value
                    };
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
