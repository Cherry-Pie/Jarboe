@extends('jarboe::layouts.main')


@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ admin_url() }}">{{ __('jarboe::common.breadcrumbs.home') }}</a></li>
        <li><a href="{{ $crud->baseUrl() }}">{{ __('jarboe::common.breadcrumbs.table') }}</a></li>
        <li>{{ __('jarboe::common.breadcrumbs.editing') }}</li>
    </ol>
@endsection

@section('content')

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
                        <h2>{{ __('jarboe::common.edit.title', ['id' => $item->getKey()]) }}</h2>
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

                            <form id="checkout-form" action="{{ $crud->editUrl($item->getKey()) }}" method="post" class="smart-form" novalidate="novalidate" enctype="multipart/form-data">

                                @csrf


                                @if (count($crud->getTabs('edit')) > 1)
                                    <div id="tabs">
                                        <ul>
                                            @foreach ($crud->getTabs() as $tabTitle => $fields)
                                                <li>
                                                    <a href="#tabs-{{ crc32($tabTitle) }}">
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
                                            <div id="tabs-{{ crc32($tabTitle) }}">
                                                <fieldset>
                                                    @include('jarboe::crud.inc.edit_tab', [
                                                        'item'     => $item,
                                                        'fields'   => $fields,
                                                        'rowsLeft' => 12,
                                                    ])
                                                </fieldset>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <fieldset>
                                        @include('jarboe::crud.inc.edit_tab', [
                                            'item'     => $item,
                                            'fields'   => $crud->getFields(),
                                            'rowsLeft' => 12,
                                        ])
                                    </fieldset>
                                @endif

                                <footer>
                                    <button type="button" class="btn btn-default" onclick="window.history.back();">
                                        {{ __('jarboe::common.edit.back') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('jarboe::common.edit.submit') }}
                                    </button>
                                </footer>
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


@endsection

@push('scripts')
<script>
    if ($('#tabs').length) {
        $('#tabs').tabs();
    }
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
