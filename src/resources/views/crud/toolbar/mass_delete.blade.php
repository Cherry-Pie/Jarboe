<div class="btn-group m-r-xs">
    <a id="mass-delete"
       class="btn btn-xs btn-danger"
       rel="tooltip"
       data-placement="top"
       data-original-title="{!! __('jarboe::toolbar.mass_delete.mass_delete_tooltip') !!}">
        {{ __('jarboe::toolbar.mass_delete.button') }}
    </a>
</div>

@push('scripts')
<script>
    $('#mass-delete').on('click', function () {
        var ids = [];
        var $inputs = $('.mass-check:checked');
        $inputs.each(function (key, input) {
            ids.push(input.value);
        });

        if (!ids.length) {
            jarboe.smallToast({
                title: "{{ __('jarboe::toolbar.mass_delete.no_rows_title') }}",
                content: "{{ __('jarboe::toolbar.mass_delete.no_rows_description') }}",
                color: "#296191",
                timeout: 4000
            });
            return;
        }

        jarboe.confirmBox({
            title: "{{ __('jarboe::toolbar.mass_delete.delete_title') }}",
            content: "{{ __('jarboe::toolbar.mass_delete.delete_description') }}",
            buttons: {
                '{{ __('jarboe::toolbar.mass_delete.delete_confirm_yes') }}': function() {
                    $('tr.jarboe-table-row').removeClass('row-error');

                    $.ajax({
                        url: '{{ $tool->getUrl() }}',
                        data: {ids: ids},
                        type: "POST",
                        success: function (response) {
                            $.each(response.removed, function (index, key) {
                                $('tr.jarboe-table-row-' + key).remove();
                            });

                            $.each(response.hidden, function (index, key) {
                                var $tr = $('tr.jarboe-table-row-' + key);
                                var $td = $tr.find('.jarboe-table-actions');
                                $td.find('.jarboe-restore, .jarboe-force-delete').show();
                                $td.find('.jarboe-delete').hide();
                            });

                            var sound = true;
                            $.each(response.errors, function (key, error) {
                                $('tr.jarboe-table-row-' + key).addClass('row-error');
                                jarboe.bigToast({
                                    title: "{{ __('jarboe::toolbar.mass_delete.delete_failed_for_row') }}" + key,
                                    content: error,
                                    color: "#C46A69",
                                    icon: "fa fa-warning shake animated",
                                    sound: sound,
                                });
                                sound = false;
                            });
                        },
                        dataType: "json"
                    });
                },
                '{{ __('jarboe::toolbar.mass_delete.delete_confirm_no') }}': null,
            }
        });
    });
</script>
@endpush
