@extends('jarboe::layouts.main')


@push('scripts')
    <script>
        var nodes = {};
    </script>
@endpush

@section('content')

<div class="row">
    <div class="col-12">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ $error }}
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="row">

    <div class="col-sm-12 col-lg-4">
        @include('jarboe::navigation.inc.tree', compact('root'))
    </div>

    <div class="col-sm-6 col-lg-4">
        @include('jarboe::navigation.inc.edit')
    </div>

    <div class="col-sm-6 col-lg-4">
        @include('jarboe::navigation.inc.create')
    </div>


</div>


@endsection

@push('styles')
    <style>
        #nestable {
            max-width: initial;
        }
    </style>
@endpush

@push('scripts')
<script src="/vendor/jarboe/js/plugin/jquery-nestable/jquery.nestable.min.js"></script>

<script>
(function($) {

    $('#nestable').nestable();
    $('#nestable').on('change', function(e) {
        var $node = $('#nestable').nestable('getMovedItem');
        var $root = $node.parent().closest('li.dd-item');
        var $prev = $node.prev();
        var $next = $node.next();

        if (!$node.data('id')) {
            return;
        }

        var data = [
            {
                name: 'id',
                value: $node.data('id')
            }
        ];

        if ($root.length) {
            data.push({
                name: 'root_id',
                value: $root.data('id')
            });
        }
        if ($prev.length) {
            data.push({
                name: 'left_id',
                value: $prev.data('id')
            });
        }
        if ($next.length) {
            data.push({
                name: 'right_id',
                value: $next.data('id')
            });
        }

        $.ajax({
            url: '{{ admin_url('admin-panel/navigation/move') }}',
            data: data,
            type: "POST",
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

})(jQuery);

</script>
@endpush