@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/tinymce/tinymce.min.js"></script>)
@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/tinymce/jquery.tinymce.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            tinymce.init({
                selector: '.tinymce-{{ $field->name() }}-{{ $locale }}',
                plugins: "code table lists autoresize",
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontsizeselect | code | table",
            });
        }, '{{ $locale }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale }}');
        });
    </script>
@endpush
