@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/clockpicker/clockpicker.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.clockpicker-field').each(function() {
                let $this = $(this);

                $this.clockpicker({
                    placement: $this.data('placement'),
                    donetext: '{{ __('jarboe::fields.time.done') }}'
                });
            });
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>
@endpush
