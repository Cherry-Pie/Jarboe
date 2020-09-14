<?php
/** @var \Yaro\Jarboe\Table\Fields\Tags $field */
?>

@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/select2/select2.min.js"></script>)

@pushonce('scripts', <script>
    jarboe.add('{{ $field->name() }}', function() {
        $('.select-2--tags').each(function() {
            const $this = $(this);

            let options = {
                tags: true,
                tokenSeparators: {!! json_encode($field->getDelimiters()) !!},
            };
            if ($this.data('relation-search-url')) {
                options.ajax = {
                    url: $this.data('relation-search-url'),
                    method: 'post',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            term: params.term || "",
                            page: params.page || 1,
                            field: $this.data('original-name'),
                        };
                    },
                    processResults: function (response) {
                        const data = [];
                        for (let item of response.results) {
                            data.push({
                                id: item.text,
                                text: item.text,
                            });
                        }
                        response.results = data;

                        return response;
                    }
                };
            }
            $this.select2(options);
        });
    }, '{{ $locale ?? 'default' }}');

    $(document).ready(function () {
        jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
    });
</script>)
