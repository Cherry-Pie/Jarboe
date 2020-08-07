@extends('jarboe::layouts.main')


@section('breadcrumbs')
    @if ($breadcrumbs->isEmptyForHistoryPage())
        <ol class="breadcrumb">
            <li><a href="{{ admin_url() }}">{{ __('jarboe::common.breadcrumbs.home') }}</a></li>
            <li><a href="{{ $crud->baseUrl() }}">{{ __('jarboe::common.breadcrumbs.table') }}</a></li>
            <li>{{ __('jarboe::common.breadcrumbs.history') }}</li>
        </ol>
    @else
        <ol class="breadcrumb">
        @foreach($breadcrumbs as $crumb)
            @if ($crumb->shouldBeShownOnHistoryPage())
                @if ($crumb->getUrl($item))
                    <li><a href="{{ $crumb->getUrl($item) }}">{{ $crumb->getTitle($item) }}</a></li>
                @else
                    <li>{{ $crumb->getTitle($item) }}</li>
                @endif
            @endif
        @endforeach
        </ol>
    @endif
@endsection

@section('content')
    <style>
        div.highlight pre.sf-dump {
            z-index: 9;
        }
    </style>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-sm">

                <!-- Timeline Content -->
                <div class="smart-timeline">
                    @if (!$versions->total() || ($versions->total() == 1 && !$item->currentVersion()->diff($version)))
                        <h3 class="text-center">{{ __('jarboe::common.history.no_versions') }}</h3>
                    @endif

                    @include('jarboe::crud.inc.history.timeline', [
                        'versions' => $versions,
                        'item' => $item,
                        'crud' => $crud,
                    ])
                </div>
                <!-- END Timeline Content -->

            </div>
        </div>
    </div>
@endsection


@pushonce('scripts', <script>
    $('body').on('click', '.jarboe-revert', function (e) {
        e.preventDefault();

        var $btn = $(this);

        jarboe.confirmBox({
            title: "{{ __('jarboe::common.history.revert_confirm_title') }}",
            content: "{{ __('jarboe::common.history.revert_confirm_description') }}",
            buttons: {
                '{{ __('jarboe::common.history.revert_confirm_yes') }}': function() {
                    $.ajax({
                        url: $btn.data('url'),
                        data: {
                            version: $btn.data('version'),
                        },
                        type: "POST",
                        success: function (response) {
                            jarboe.smallToast({
                                title: $btn.data('revert_success_title'),
                                content: $btn.data('revert_success_description'),
                                color: "#659265",
                                iconSmall: "fa fa-check fa-2x fadeInRight animated",
                                timeout: 4000
                            });

                            $('.smart-timeline').html(response.timeline);
                            if (!ismobile) {
                                $("[rel=tooltip], [data-rel=tooltip]").tooltip();
                            }
                        },
                        error: function (xhr, status, error) {
                            var response = JSON.parse(xhr.responseText);
                            jarboe.smallToast({
                                title: "{{ __('jarboe::common.history.revert_failed') }}",
                                content: response.message,
                                color: "#C46A69",
                                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                                timeout: 4000
                            });
                        },
                        dataType: "json"
                    });
                },
                '{{ __('jarboe::common.history.revert_confirm_no') }}': null,
            }
        });
    });
</script>)
