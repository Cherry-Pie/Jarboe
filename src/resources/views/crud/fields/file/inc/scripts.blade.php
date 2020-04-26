@push('scripts')
<script>
    Jarboe.add('{{ $field->name() }}', function() {
        const $list = $('.file-field-dd-list');
        $list.sortable({
            axis: 'y',
            cursor: 'grabbing',
            opacity: 0.5,
            placeholder: 'row-dragging dd-item dd-item-drag',
            delay: 150,
            forcePlaceholderSize: true
        });
        $list.disableSelection();

        $list.find('.delete-file-btn').unbind('click').on('click', function() {
            const $btn = $(this);
            jarboe.confirmBox({
                title: "{{ __('jarboe::fields.file.confirmbox_title') }}",
                content: "{{ __('jarboe::fields.file.confirmbox_description') }}",
                buttons: {
                    '{{ __('jarboe::fields.file.confirmbox_yes') }}': function() {
                        $btn.closest('.dd-item').slideUp('normal', function() {
                            $(this).remove();
                        });
                    },
                    '{{ __('jarboe::fields.file.confirmbox_no') }}': null,
                },
            });
        });
    }, '{{ $locale ?? 'default' }}');

    $(document).ready(function () {
        Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
    });
</script>
@endpush
