<?php
/** @var \Yaro\Jarboe\Helpers\System $system */
?>
<!-- PAGE FOOTER -->
<div class="page-footer">
    <div class="row">

        <div class="col-xs-8 col-sm-8">
            <span class="txt-color-white"></span>
        </div>


        <div class="col-xs-4 col-sm-4 text-right hidden-xs">
        <div class="txt-color-white inline-block">
            <div class="btn-group dropup">
                <button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
                    <i class="fa fa-link"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right text-left">
                    <li>
                        <div class="padding-5">
                            <p class="txt-color-darken font-sm no-margin">
                                Jarboe: {{ $jarboeVersion }}
                                <a id="check-newer-version" href="javascript:void(0);" class="btn btn-default btn-xs pull-right" rel="tooltip" data-placement="top" data-original-title="{{ __('jarboe::misc.system.check_new_version') }}">
                                    <i class="fa fa-refresh"></i>
                                </a>
                            </p>
                        </div>
                    </li>
                    <li>
                        <div class="padding-5">
                            <p class="txt-color-darken font-sm no-margin">
                                Laravel: {{ $laravelVersion }}
                            </p>
                        </div>
                    </li>
                    <li class="divider"></li>

                    <li class="jsystem-loadaverage">
                        <div class="padding-5">
                            <p class="txt-color-darken font-sm no-margin">
                                {{ __('jarboe::misc.system.load_average') }}
                                <a href="javascript:void(0);" class="pull-right system-explanation" rel="tooltip" data-placement="left" data-original-title="{{ implode(' ', $system->systemLoadSamples()) }}">
                                    <i class="fa fa-question-circle" style="color: #3c79ab;"></i>
                                </a>
                            </p>

                            @foreach ($system->systemLoadSamplesInPercentages() as $percentage)
                                <div class="progress progress-micro no-margin">
                                    <div class="progress-bar progress-bar-{{ $percentage < 55 ? 'success' : ($percentage < 75 ? 'warning' : 'danger') }}"
                                         style="width: {{ $percentage }}%;"></div>
                                </div>
                            @endforeach
                        </div>
                    </li>
                    <li class="divider"></li>

                    <li class="jsystem-memory">
                        <div class="padding-5">
                            <p class="txt-color-darken font-sm no-margin">
                                {{ __('jarboe::misc.system.memory') }}
                                <a href="javascript:void(0);" class="pull-right system-explanation" rel="tooltip" data-placement="left"
                                   @if (is_null($system->memoryTotal()))
                                    data-original-title="{{ 'NA' }}"
                                   @else
                                    data-original-title="{{ $system->readableSize($system->memoryUsed()) .' / '. $system->readableSize($system->memoryTotal()) }}"
                                   @endif
                                >
                                    <i class="fa fa-question-circle" style="color: #3c79ab;"></i>
                                </a>
                            </p>

                            <div class="progress progress-micro no-margin">
                                <div class="progress-bar progress-bar-{{ $system->memoryPercentage() < 70 ? 'success' : ($system->memoryPercentage() < 85 ? 'warning' : 'danger') }}"
                                     style="width: {{ $system->memoryPercentage() }}%;"></div>
                            </div>
                        </div>
                    </li>
                    <li class="jsystem-swap">
                        <div class="padding-5">
                            <p class="txt-color-darken font-sm no-margin">
                                {{ __('jarboe::misc.system.swap') }}
                                <a href="javascript:void(0);" class="pull-right system-explanation" rel="tooltip" data-placement="left"
                                   @if (is_null($system->swapTotal()))
                                    data-original-title="{{ 'NA' }}"
                                   @else
                                    data-original-title="{{ $system->readableSize($system->swapUsed()) .' / '. $system->readableSize($system->swapTotal()) }}"
                                   @endif
                                >
                                    <i class="fa fa-question-circle" style="color: #3c79ab;"></i>
                                </a>
                            </p>

                            <div class="progress progress-micro no-margin">
                                <div class="progress-bar progress-bar-{{ $system->swapPercentage() < 70 ? 'success' : ($system->swapPercentage() < 85 ? 'warning' : 'danger') }}"
                                     style="width: {{ $system->swapPercentage() }}%;"></div>
                            </div>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <div class="padding-5">
                            <a href="javascript:void(0);" class="btn btn-block btn-default jsystem-refresh">
                                {{ __('jarboe::misc.system.refresh') }}
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    </div>
</div>
<!-- END PAGE FOOTER -->



@push('scripts')
<script>
    $(document).ready(function() {
        $('.jsystem-refresh').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            $button.addClass('disabled');
            $.get('{{ route('refresh_system_values') }}', function(response) {
                let alertType = '';
                const $dropdown = $button.closest('ul');

                const $swap = $dropdown.find('.jsystem-swap');
                $swap.find('.system-explanation').attr('data-original-title', response.swap.explanation);
                const $swapProgressBar = $swap.find('.progress .progress-bar');
                $swapProgressBar.removeClassPrefix('progress-bar-');
                alertType = response.swap.percentage < 70 ? 'success' : (response.swap.percentage < 85 ? 'warning' : 'danger');
                $swapProgressBar.addClass('progress-bar-'+ alertType);
                $swapProgressBar.css('width', response.swap.percentage +'%');

                const $memory = $dropdown.find('.jsystem-memory');
                $memory.find('.system-explanation').attr('data-original-title', response.memory.explanation);
                const $memoryProgressBar = $memory.find('.progress .progress-bar');
                $memoryProgressBar.removeClassPrefix('progress-bar-');
                alertType = response.memory.percentage < 70 ? 'success' : (response.memory.percentage < 85 ? 'warning' : 'danger');
                $memoryProgressBar.addClass('progress-bar-'+ alertType);
                $memoryProgressBar.css('width', response.memory.percentage +'%');

                const $loadAverage = $dropdown.find('.jsystem-loadaverage');
                $loadAverage.find('.system-explanation').attr('data-original-title', response.load_average.explanation);
                const $loadProgressBars = $loadAverage.find('.progress .progress-bar');
                $loadProgressBars.removeClassPrefix('progress-bar-');
                for (let index in response.load_average.percentages) {
                    let percentage = response.load_average.percentages[index];
                    alertType = percentage < 55 ? 'success' : (percentage < 75 ? 'warning' : 'danger');
                    let $loadProgressBar = $($loadProgressBars.get(index));
                    $loadProgressBar.addClass('progress-bar-'+ alertType);
                    $loadProgressBar.css('width', percentage +'%');
                }

                $button.removeClass('disabled');
            }, 'json');
        });

        $('#check-newer-version').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            checkVersion(function (response) {
                if (response.ok) {
                    jarboe.smallToastSuccess('{{ __('jarboe::misc.check_version.ok_title') }}', '{{ __('jarboe::misc.check_version.ok_description') }}', null);
                } else {
                    jarboe.smallToastDanger('{{ __('jarboe::misc.check_version.nonok_title') }}', '{{ __('jarboe::misc.check_version.nonok_description') }}', null);
                }
            });
        });

        function checkVersion(callback) {
            callback = callback || function() {};

            let saved = localStorage.getItem('_jv');
            if (saved) {
                saved = JSON.parse(saved);
                let date = new Date(saved.date);
                date.setDate(date.getDate() + 7);
                if (date > new Date) {
                    callback(saved.response);
                    return;
                }
            }

            $.get('https://jarboe.app/api/check-version/{{ \Yaro\Jarboe\Jarboe::VERSION }}', function(response) {
                localStorage.setItem('_jv', JSON.stringify({
                    response: response,
                    date: new Date,
                }));
                callback(response);
            }, 'json');
        }

        checkVersion();
    });
</script>
@endpush
