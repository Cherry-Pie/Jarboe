<a class="btn btn-default btn-sm jarboe-restore"
   data-id="{{ $model->getKey() }}"
   data-url="{{ $crud->restoreUrl($model->getKey()) }}"
   @if (!$isVisible)
     style="display: none;"
   @endif
   href="javascript:void(0);">
    <i class="fa fa-history"></i>
</a>

@pushonce('scripts', <script>
    $('.jarboe-restore').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);

        jarboe.confirmBox({
            title: "{{ __('jarboe::common.list.restore_title') }}",
            content: "{{ __('jarboe::common.list.restore_description') }}",
            buttons: {
                '{{ __('jarboe::common.list.restore_confirm_yes') }}': function () {
                    $.ajax({
                        url: $btn.data('url'),
                        type: "POST",
                        success: function (response) {
                            jarboe.smallToast({
                                title: "{{ __('jarboe::common.list.restore_success') }}",
                                content: response.message,
                                color: "#659265",
                                iconSmall: "fa fa-check fa-2x fadeInRight animated",
                                timeout: 4000
                            });

                            var $td = $btn.closest('.jarboe-table-actions');
                            $td.find('.jarboe-restore, .jarboe-force-delete').hide();
                            $td.find('.jarboe-delete').show();
                        },
                        error: function (xhr, status, error) {
                            var response = JSON.parse(xhr.responseText);
                            jarboe.smallToast({
                                title: "{{ __('jarboe::common.list.restore_failed') }}",
                                content: response.message,
                                color: "#C46A69",
                                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                                timeout: 4000
                            });
                        },
                        dataType: "json"
                    });
                },
                '{{ __('jarboe::common.list.restore_confirm_no') }}': null
            },
        });
    });
</script>)