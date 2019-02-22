<div class="dd" id="nestable">
    <ol class="dd-list">

        @foreach ($root->getImmediateDescendants() as $node)
            @if ($node->isLeaf())
                @include('jarboe::navigation.inc.tree_leaf')
            @else
                @include('jarboe::navigation.inc.tree_branch')
            @endif
        @endforeach

    </ol>
</div>

@push('scripts')
    <script>
        $('#nestable .delete-node').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);

            $.SmartMessageBox({
                title : "Delete node `"+ $btn.data('name') +"`?",
                content : "This action cannot be undone.",
                buttons : '[No][Yes]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Yes") {
                    $btn.closest('form').submit();
                }
            });
        });

        $('#nestable .edit-node').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var $form = $('#edit-node-form');

            var data = nodes[$btn.data('id')];
            $form.find('[name="id"]').val(data.id);
            $form.find('[name="name"]').val(data.name);
            $form.find('[name="slug"]').val(data.slug);
            $form.find('[name="icon"]').val(data.icon);
            $form.find('.input-group-addon i.fa').removeClass().addClass('fa').addClass(data.icon);
            $form.find('[name="is_active"]').attr('checked', !!data.is_active);

            $form.show();
        });


        $('#nestable input[name="is_active"]').on('change', function(e) {
            $.ajax({
                url: '{{ admin_url('admin-panel/navigation/update') }}',
                data: {
                    'id': $(this).data('id'),
                    'is_active': this.checked
                },
                type: "PATCH",
                success: function(response) {
                    $.smallBox({
                        title : "Success",
                        content: response.message,
                        color : "#659265",
                        iconSmall : "fa fa-check fa-2x fadeInRight animated",
                        timeout : 4000
                    });
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    $.smallBox({
                        title : "Failed",
                        content: response.message,
                        color : "#C46A69",
                        iconSmall : "fa fa-times fa-2x fadeInRight animated",
                        timeout : 4000
                    });
                },
                dataType: "json"
            });
        });
    </script>
@endpush