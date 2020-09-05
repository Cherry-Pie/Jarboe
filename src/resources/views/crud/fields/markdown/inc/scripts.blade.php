<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Markdown $field
 */
?>

@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/markdown/markdown.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/markdown/to-markdown.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/markdown/bootstrap-markdown.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.markdown-field').markdown({
                autofocus: false,
            });
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>

    <script>
        $('label.translation-{{ $field->name() }}-locale-label').on('click', function() {
            $('.locale-field-{{ $field->name() }}').hide();
            $('.locale-field-{{ $field->name() }}-'+ $(this).data('locale')).show();
        });
    </script>
@endpush
