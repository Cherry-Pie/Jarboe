<a
    @if ($action->getTooltip())
        rel="tooltip"
        data-placement="{{ $action->getTooltipPosition() }}"
        data-original-title="{{ $action->getTooltip() }}"
    @endif
    class="btn btn-default btn-sm"
    href="{{ $crud->createUrl() }}">
    {{ __('jarboe::common.list.create') }}
</a>
