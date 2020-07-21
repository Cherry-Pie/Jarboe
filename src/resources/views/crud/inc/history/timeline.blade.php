<ul class="smart-timeline-list">
    @foreach ($versions as $version)
        @if (!$item->currentVersion()->diff($version))
            @continue
        @endif

        <li>
            <div class="smart-timeline-icon">
                @if ($version->responsible_user)
                    <img src="{{ $version->responsible_user->avatar_url ?: '/vendor/jarboe/img/avatars/default.png' }}" width="32" height="32"
                         rel="tooltip"
                         data-container="body"
                         data-placement="right"
                         data-original-title="{{ $version->responsible_user->id }}: {{ $version->responsible_user->name }}"/>
                @else
                    <img src="/vendor/jarboe/img/avatars/default.png" width="32" height="32"/>
                @endif
            </div>
            <div class="smart-timeline-time">
                <small>{{ $version->created_at->diffForHumans() }}</small>
            </div>
            <div class="smart-timeline-content">


                <div class="row">
                    <div class="col-md-10">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            @foreach ($item->currentVersion()->diff($version) as $key => $value)
                                <?php
                                /** @var \Yaro\Jarboe\Table\Fields\AbstractField $field */
                                $field = $crud->getFieldByName($key);
                                ?>
                                <tr>
                                    <td>
                                        @if ($field)
                                            {{ $field->title() }} <small>[{{ $field->name() }}]</small>
                                        @else
                                            {{ $key }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($field)
                                            {!! $field->getHistoryView($value) !!}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="highlight">
                            @dump($item->currentVersion()->diff($version))
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align: center;">
                        <span class="label label-info">{{ $version->created_at->format('Y-m-d H:i:s O') }}</span>
                        <hr>
                        <a href="javascript:void(0);"
                           class="btn bg-color-blue txt-color-white jarboe-revert"
                           data-id="{{ $item->getKey() }}"
                           data-url="{{ $crud->revertUrl($item->getKey()) }}"
                           data-version="{{ $version->getKey() }}"
                           data-revert_success_title="{{ __('jarboe::common.history.revert_success_title') }}"
                           data-revert_success_description="{{ __('jarboe::common.history.revert_success_description', ['datetime' => $version->created_at->format('Y-m-d H:i:s O')]) }}"
                        >{{ __('jarboe::common.history.revert_button') }}</a>
                    </div>
                </div>

            </div>
        </li>
    @endforeach


    <li class="text-center">
        <div class="btn-group">
            <a href="{{ $versions->previousPageUrl() }}" class="btn btn-default {{ $versions->onFirstPage() ? 'disabled' : '' }}">
                &nbsp;&nbsp;
                <i class="fa fa-chevron-left"></i>
                &nbsp;&nbsp;
            </a>
            <a href="{{ $versions->nextPageUrl() }}" class="btn btn-default {{ $versions->hasMorePages() ? '' : 'disabled' }}">
                &nbsp;&nbsp;
                <i class="fa fa-chevron-right"></i>
                &nbsp;&nbsp;
            </a>
        </div>
    </li>
</ul>
