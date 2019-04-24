@extends('jarboe::layouts.main')

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ admin_url() }}">{{ __('jarboe::common.breadcrumbs.home') }}</a></li>
        <li>{{ __('jarboe::common.breadcrumbs.table') }}</li>
    </ol>
@endsection

@section('content')

    <!-- widget grid -->
    <section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
                     data-widget-togglebutton="false"
                     data-widget-collapsed="false"
                     data-widget-colorbutton="false"
                     data-widget-sortable="false"
                     data-widget-deletebutton="false"
                     data-widget-editbutton="false">
                    <!-- widget options:
                    usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                    data-widget-colorbutton="false"
                    data-widget-editbutton="false"
                    data-widget-togglebutton="false"
                    data-widget-deletebutton="false"
                    data-widget-fullscreenbutton="false"
                    data-widget-custombutton="false"
                    data-widget-collapsed="true"
                    data-widget-sortable="false"

                    -->
                    <header>
                        @include('jarboe::crud.inc.toolbar_header', [
                            'crud'  => $crud,
                            'tools' => $crud->getActiveHeaderToolbarTools(),
                        ])
                    </header>

                    <!-- widget div-->
                    <div>

                        <!-- widget content -->
                        <div class="widget-body no-padding">

                            @include('jarboe::crud.inc.toolbar_body', [
                                'crud'  => $crud,
                                'tools' => $crud->getActiveBodyToolbarToolsOnTop(),
                            ])

                            <div class="table-responsive">

                                <table class="table table-bordered table-stripped table-hover" width="100%">
                                    <thead>
                                        <tr>

                                            @if ($crud->isBatchCheckboxesEnabled())
                                            <th class="check-all-column smart-form">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="checkbox-inline">
                                                    <i rel="tooltip" data-placement="right" data-original-title="[un]check&nbsp;all"></i>
                                                </label>
                                            </th>
                                            @endif

                                            @include('jarboe::crud.inc.list_headers', [
                                                'crud'   => $crud,
                                                'fields' => $crud->getColumnsAsFields(),
                                            ])
                                            <th class="jarboe-table-actions" style="width:1%; min-width: 90px; text-align: center;">
                                                @if ($crud->actions()->isAllowed('create'))
                                                    {!! $crud->actions()->find('create')->render($crud) !!}
                                                @endif
                                            </th>
                                        </tr>
                                        @if ($crud->hasAnyFieldFilter())
                                            <tr class="smart-form">
                                                <form method="post" action="{{ $crud->searchUrl() }}" class="smart-form">
                                                    @csrf

                                                    @if ($crud->isBatchCheckboxesEnabled())
                                                        <th></th>
                                                    @endif

                                                    @include('jarboe::crud.inc.list_filters', [
                                                        'fields' => $crud->getColumnsAsFields(),
                                                    ])

                                                    <th class="jarboe-table-actions" style="text-align: center;">
                                                        <button class="btn btn-default btn-sm" type="submit">{{ __('jarboe::common.list.search') }}</button>
                                                    </th>
                                                </form>
                                            </tr>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr class="jarboe-table-row jarboe-table-row-{{ $item->getKey() }}">

                                                @if ($crud->isBatchCheckboxesEnabled())
                                                <td class="smart-form">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="mass[]" value="{{ $item->getKey() }}" class="mass-check">
                                                        <i></i>
                                                    </label>
                                                </td>
                                                @endif

                                                @include('jarboe::crud.inc.list_row', [
                                                    'item'   => $item,
                                                    'fields' => $crud->getColumnsAsFields(),
                                                ])

                                                <td class="jarboe-table-actions" style="text-align: center;">
                                                    @foreach ($crud->actions()->getRowActions() as $action)
                                                        @if ($action->isAllowed($item))
                                                            {!! $action->render($crud, $item) !!}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if (!count($items))
                                            <div class="alert alert-info fade in" style="margin: 0;">
                                                <button class="close" data-dismiss="alert">
                                                    ×
                                                </button>
                                                <i class="fa-fw fa fa-info"></i>
                                                <strong>Info!</strong> No records.
                                            </div>
                                        @endif

                                    </tbody>
                                </table>

                                <div class="dt-toolbar-footer" style="position: sticky; left: 0; right: 0;">
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="dataTables_info" style="height: 36px;">
                                            {{ __('jarboe::common.list.pagination', [
                                                'from' => (int) $items->firstItem(),
                                                'to' => (int) $items->lastItem(),
                                                'total' => (int) $items->total(),
                                            ]) }}
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="col-xs-6 col-sm-6" style="text-align: right;">
                                        <div class="dataTables_paginate paging_simple_numbers" style="float: initial; height: 36px;">
                                            {!! $items->links() !!}
                                        </div>
                                        <hr>
                                        @if (is_array($crud->getRawPerPage()))
                                            <div class="btn-group">
                                                @foreach($crud->getRawPerPage() as $perPage => $perPageTitle)
                                                    <button type="button" class="btn btn-default jarboe-per-page {{ $crud->getPerPageParam() == (is_numeric($perPage) ? $perPageTitle : $perPage) ? 'active' : '' }}" data-url="{{ $crud->perPageUrl(is_numeric($perPage) ? $perPageTitle : $perPage) }}">
                                                        {{ $perPageTitle }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @include('jarboe::crud.inc.toolbar_body', [
                                'crud'  => $crud,
                                'tools' => $crud->getActiveBodyToolbarToolsOnBottom(),
                            ])
                        </div>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end widget -->


            </article>
            <!-- WIDGET END -->


        </div>

        <!-- end row -->


    </section>
    <!-- end widget grid -->




@endsection



@push('styles')
    <style>
        .table-responsive .table tr td,
        .table-responsive .table tr th {
            vertical-align: middle;
        }
        .table-responsive .table tr th.hasinput .form-control {
            font-weight: 400;
        }
        .jarboe-table-actions {
            text-align: center;
            position: sticky;
            background-color: white;
            right: 0px;
        }
        ul.pagination {
            float: initial !important;
        }
        thead .sorting,
        thead .sorting_asc,
        thead .sorting_asc_disabled,
        thead .sorting_desc,
        thead .sorting_desc_disabled {
            cursor: pointer;
            padding-right: 18px !important;
        }
        thead .sorting {
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATAgMAAAAPPt5fAAAACVBMVEUAAADIyMjl5eVIBBP/AAAAAXRSTlMAQObYZgAAAClJREFUCNdjwAYEwGQKiGDsBJFsTA5AUoJhAqZaiDhEDVg9RC/MHEwAANsMA91AQfd/AAAAAElFTkSuQmCC) no-repeat center right
        }
        thead .sorting_asc,
        thead .sorting_desc {
            background-color: #eee;
        }
        thead .sorting_asc {
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATAQMAAABInqSPAAAABlBMVEUAAABVVVUT3Vn+AAAAAXRSTlMAQObYZgAAAB1JREFUCNdjwAkcgPgBAwPjBwYG5h8MDOx/GAgBAKRJBBCQbHkwAAAAAElFTkSuQmCC) no-repeat center right;
        }
        thead .sorting_asc_disabled {
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATAQMAAABInqSPAAAABlBMVEUAAABUVFR8AzIeAAAAAnRSTlMAf7YpoZUAAAAdSURBVAjXY8AJHID4AQMD4wcGBuYfDAzsfxgIAQCkSQQQkGx5MAAAAABJRU5ErkJggg==) no-repeat center right;
        }
        thead .sorting_desc {
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATAQMAAABInqSPAAAABlBMVEUAAABVVVUT3Vn+AAAAAXRSTlMAQObYZgAAABxJREFUCNdjIATY/zAwMP9gYGD8AOQ8AGIHnEoBkNkEEEbbutQAAAAASUVORK5CYII=) no-repeat center right;
        }
        thead .sorting_desc_disabled {
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATAQMAAABInqSPAAAABlBMVEUAAABUVFR8AzIeAAAAAnRSTlMAf7YpoZUAAAAcSURBVAjXYyAE2P8wMDD/YGBg/ADkPABiB5xKAZDZBBBG27rUAAAAAElFTkSuQmCC) no-repeat center right;
        }

        .jarboe-table-row.row-error {
            background-color: #ffefef;
        }
    </style>
@endpush

@push('scripts')
<script>
(function($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
})(jQuery);


(function($) {

var CRUD =
{

    init: function()
    {
        $('.check-all-column input').on('change', function() {
            $('.mass-check').attr('checked', this.checked).prop('checked', this.checked);
        });

        $('.jarboe-per-page').on('click', function(e) {
            window.location.href = $(this).data('url');
        });

        $('.sorting').on('click', function() {
            window.location.href = $(this).data('url');
        });

    },
};

CRUD.init();

})(jQuery);

</script>
@endpush