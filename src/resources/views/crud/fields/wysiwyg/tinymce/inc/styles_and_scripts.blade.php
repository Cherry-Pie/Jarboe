@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/tinymce/tinymce.min.js"></script>)
@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/tinymce/jquery.tinymce.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            let options = {!! json_encode($field->getOptions()) !!};
            options.selector = '.tinymce-{{ $field->name() }}-{{ $locale }}';
            tinymce.init(options);
        }, '{{ $locale }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale }}');
        });
    </script>
@endpush
