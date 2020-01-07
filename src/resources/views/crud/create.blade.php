@extends('jarboe::layouts.main')

@section('breadcrumbs')
    @if ($breadcrumbs->isEmptyForCreatePage())
        <ol class="breadcrumb">
            <li><a href="{{ admin_url() }}">{{ __('jarboe::common.breadcrumbs.home') }}</a></li>
            <li><a href="{{ $crud->baseUrl() }}">{{ __('jarboe::common.breadcrumbs.table') }}</a></li>
            <li>{{ __('jarboe::common.breadcrumbs.creating') }}</li>
        </ol>
    @else
        <ol class="breadcrumb">
            @foreach($breadcrumbs as $crumb)
                @if ($crumb->shouldBeShownOnCreatePage())
                    @if ($crumb->getUrl())
                        <li><a href="{{ $crumb->getUrl() }}">{{ $crumb->getTitle() }}</a></li>
                    @else
                        <li>{{ $crumb->getTitle() }}</li>
                    @endif
                @endif
            @endforeach
        </ol>
    @endif
@endsection

@section('content')

    @foreach($viewsAbove as $viewAbove)
        {!! $viewAbove !!}
    @endforeach

    @include('jarboe::crud.inc.errors_on_top', [
        'crud' => $crud,
        'errors' => $errors,
    ])

    <!-- widget grid -->
    <section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

            <!-- NEW COL START -->
            <article class="{{ $crud->formClass() }}">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget" id="wid-id-1"
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
                        <h2>{{ __('jarboe::common.create.title') }}</h2>
                    </header>

                    <!-- widget div-->
                    <div>

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->

                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body no-padding">

                            <form id="checkout-form" action="{{ $crud->createUrl() }}" method="post" class="smart-form" novalidate="novalidate" enctype="multipart/form-data">

                                {{ csrf_field() }}



                                @if (count($crud->getTabs('create')) > 1)
                                    <div id="tabs">
                                        <ul>
                                            @foreach ($crud->getTabs() as $tabTitle => $fields)
                                                <li>
                                                    <a href="#tab-{{ urlify($tabTitle) }}">
                                                        {{ $tabTitle }}
                                                        @if ($count = $crud->getTabErrorsCount($tabTitle, $errors))
                                                            <em style="
        font-size: 10px;
        display: block;
        padding: 2px;
        position: absolute;
        top: 1px;
        right: 1px;
        text-decoration: none;
        font-style: normal;
        background: #ED1C24;
        color: #fff;
        min-width: 8px;
        border-radius: 50%;
        line-height: 8px;
        font-weight: 700;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
        border: 1px solid rgba(255,255,255,.1);
    ">{{ $count }}</em>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>

                                        @foreach ($crud->getTabs() as $tabTitle => $fields)
                                            <div id="tab-{{ urlify($tabTitle) }}">
                                                <fieldset>
                                                    @include('jarboe::crud.inc.create.tab', [
                                                        'fields'   => $fields,
                                                        'rowsLeft' => 12,
                                                    ])
                                                </fieldset>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <fieldset>
                                        @include('jarboe::crud.inc.create.tab', [
                                            'fields'   => $crud->getFields(),
                                            'rowsLeft' => 12,
                                        ])
                                    </fieldset>
                                @endif


                                @include('jarboe::crud.inc.create.form_footer', [
                                    'crud' => $crud,
                                ])
                            </form>

                        </div>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end widget -->

            </article>
            <!-- END COL -->


        </div>

        <!-- end row -->


    </section>
    <!-- end widget grid -->


    @foreach($viewsBelow as $viewBelow)
        {!! $viewBelow !!}
    @endforeach

@endsection


@push('scripts')
<script>
    if ($('#tabs').length) {
        $('#tabs').tabs({
            create: function (event, ui) {
                // Adjust hashes to not affect URL when clicked.
                var widget = $('#tabs').data('uiTabs');
                widget.panels.each(function (i) {
                    this.id = 'uiTab_' + this.id;
                    widget.anchors[i].hash = '#' + this.id;
                    $(widget.tabs[i]).attr('aria-controls', this.id);
                });
            },
            activate: function (event, ui) {
                // Update the window URL bar with the original "clean' tab id.
                window.location.hash = ui.newPanel.attr('id').replace('uiTab_', '');
            },
        });
    }

    $('#checkout-form').on('submit', function () {
        $('.btn-form-submit').attr('disabled', true);
    });
</script>
@endpush

@push('styles')
<style>
    #tabs {
        margin: 12px;
    }
    .no-padding .note-editor.note-frame {
        border: 1px solid #a9a9a9;
    }
</style>
@endpush
