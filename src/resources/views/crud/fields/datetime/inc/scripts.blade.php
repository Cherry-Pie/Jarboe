@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.datetimepicker-field').each(function() {
                let $this = $(this);

                let options = {
                    format: $this.data(jarboe.kebabCase('format')),
                    sideBySide: true,
                };
                if ($this.data(jarboe.kebabCase('defaultDate'))) {
                    options['defaultDate'] = $this.data(jarboe.kebabCase('defaultDate'));
                }

                $this.datetimepicker(options).on('dp.change', function(e) {
                    $(this).parent().find('.datetimepicker-value-field').val(e.date.format('YYYY-MM-DD HH:mm:ss'));
                });

                if ($this.val()) {
                    $this.val(
                        moment($this.val()).format(
                            $this.data(jarboe.kebabCase('format'))
                        )
                    );
                }
            });
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>
@endpush
