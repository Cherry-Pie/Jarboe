@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/tinymce/tinymce.min.js"></script>)
@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/tinymce/jquery.tinymce.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.wysiwyg-tinymce-field').each(function() {
                const $this = $(this);
                let options = $this.data('options');
                if ($this.is(':disabled')) {
                    options.readonly = true;
                }
                $this.tinymce(options);
            });
        }, '{{ $locale }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale }}');
        });
    </script>
@endpush

@pushonce('style_files',
<style>
    label.state-error div.tox-tinymce {
        border-color: #A90329 !important;
    }
</style>)