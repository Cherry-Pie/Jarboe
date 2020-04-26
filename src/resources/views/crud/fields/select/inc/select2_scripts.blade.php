@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/select2/select2.min.js"></script>)

@pushonce('scripts', <script>
    jarboe.add('{{ $field->name() }}', function() {
        $('.select2-field').each(function() {
            const $this = $(this);

            let options = {};
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
