<a class="btn btn-default btn-sm jarboe-delete"
   data-id="{{ $model->getKey() }}"
   data-url="{{ $crud->deleteUrl($model->getKey()) }}"
   data-soft-delete="{{ (int) $crud->isSoftDeleteEnabled() }}"
   @if (!$isVisible)
    style="display: none;"
   @endif
   href="javascript:void(0);">
    <i class="fa fa-times"></i>
</a>

@pushonce('scripts', <script>
  $('.jarboe-delete').on('click', function(e) {
    e.preventDefault();

    var $btn = $(this);

    $.SmartMessageBox({
      title : "{{ __('jarboe::common.list.delete_title') }}",
      content : "{{ $crud->isSoftDeleteEnabled() ? __('jarboe::common.list.soft_delete_description') : __('jarboe::common.list.delete_description') }}",
      buttons : '[{{ __('jarboe::common.list.delete_confirm_no') }}][{{ __('jarboe::common.list.delete_confirm_yes') }}]'
    }, function(ButtonPressed) {
      if (ButtonPressed === "{{ __('jarboe::common.list.delete_confirm_yes') }}") {
        $.ajax({
          url: $btn.data('url'),
          type: "POST",
          success: function(response) {
            $.smallBox({
              title : "{{ __('jarboe::common.list.delete_success') }}",
              content: response.message,
              color : "#659265",
              iconSmall : "fa fa-check fa-2x fadeInRight animated",
              timeout : 4000
            });

            if ($btn.data('soft-delete') && response.type == 'hidden') {
              $btn.hide();
              var $td = $btn.closest('.jarboe-table-actions');
              $td.find('.jarboe-restore, .jarboe-force-delete').show();
            } else if (response.type == 'removed') {
              $btn.closest('.jarboe-table-row').remove();
            }
          },
          error: function(xhr, status, error) {
            var response = JSON.parse(xhr.responseText);
            $.smallBox({
              title : "{{ __('jarboe::common.list.delete_failed') }}",
              content: response.message,
              color : "#C46A69",
              iconSmall : "fa fa-times fa-2x fadeInRight animated",
              timeout : 4000
            });
          },
          dataType: "json"
        });
      }
    });
  });
</script>)