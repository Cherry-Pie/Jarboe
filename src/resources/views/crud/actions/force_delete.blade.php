<a class="btn btn-default btn-sm jarboe-force-delete"
   data-id="{{ $model->getKey() }}"
   data-url="{{ $crud->forceDeleteUrl($model->getKey()) }}"
   @if (!$isVisible)
     style="display: none;"
   @endif
   href="javascript:void(0);">
    <i class="fa fa-trash-o"></i>
</a>

@pushonce('scripts', <script>
  $('.jarboe-force-delete').on('click', function(e) {
    e.preventDefault();

    var $btn = $(this);

    $.SmartMessageBox({
      title : "{{ __('jarboe::common.list.force_delete_title') }}",
      content : "{{ __('jarboe::common.list.force_delete_description') }}",
      buttons : '[{{ __('jarboe::common.list.force_delete_confirm_no') }}][{{ __('jarboe::common.list.force_delete_confirm_yes') }}]'
    }, function(ButtonPressed) {
      if (ButtonPressed === "{{ __('jarboe::common.list.force_delete_confirm_yes') }}") {
        $.ajax({
          url: $btn.data('url'),
          type: "POST",
          success: function(response) {
            $.smallBox({
              title : "{{ __('jarboe::common.list.force_delete_success') }}",
              content: response.message,
              color : "#659265",
              iconSmall : "fa fa-check fa-2x fadeInRight animated",
              timeout : 4000
            });

            $btn.closest('.jarboe-table-row').remove();
          },
          error: function(xhr, status, error) {
            var response = JSON.parse(xhr.responseText);
            $.smallBox({
              title : "{{ __('jarboe::common.list.force_delete_failed') }}",
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