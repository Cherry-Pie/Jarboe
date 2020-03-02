<a class="btn btn-default btn-sm"
   @if ($action->getTooltip())
       rel="tooltip"
       data-placement="{{ $action->getTooltipPosition() }}"
       data-original-title="{{ $action->getTooltip() }}"
   @endif
   href="{{ $crud->editUrl($model->getKey()) }}">
    <i class="fa fa-pencil"></i>
</a>
