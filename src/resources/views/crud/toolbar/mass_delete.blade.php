<div class="col-xs-9 col-sm-5 col-md-5 col-lg-5">
    <div class="btn-group">
        <a id="mass-delete"
           class="btn btn-xs btn-danger"
           rel="tooltip"
           data-placement="top"
           data-original-title="{!! __('jarboe::toolbar.mass_delete.mass_delete_tooltip') !!}">
            <span class="fa fa-times"></span>
        </a>
    </div>
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
          $.smallBox({
            title: "{{ __('jarboe::toolbar.mass_delete.no_rows_title') }}",
            content: "{{ __('jarboe::toolbar.mass_delete.no_rows_description') }}",
            color: "#296191",
            timeout: 4000
          });
          return;
        }

        $.SmartMessageBox({
          title: "{{ __('jarboe::toolbar.mass_delete.delete_title') }}",
          content: "{{ __('jarboe::toolbar.mass_delete.delete_description') }}",
          buttons: '[{{ __('jarboe::toolbar.mass_delete.delete_confirm_no') }}][{{ __('jarboe::toolbar.mass_delete.delete_confirm_yes') }}]'
        }, function (ButtonPressed) {
          if (ButtonPressed === "{{ __('jarboe::toolbar.mass_delete.delete_confirm_yes') }}") {
            $('tr.jarboe-table-row').removeClass('row-error');

            $.ajax({
              url: '{{ $tool->getUrl() }}',
              data: {ids: ids},
              type: "POST",
              success: function (response) {
                $.each(response.removed, function (index, key) {
                  $('tr.jarboe-table-row-' + key).remove();
                });

                var sound = true;
                $.each(response.errors, function (key, error) {
                  $('tr.jarboe-table-row-' + key).addClass('row-error');
                  $.bigBox({
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
          }
        });
      });
    </script>
@endpush
