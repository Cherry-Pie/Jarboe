<?php
/** @var \Yaro\Jarboe\Table\Fields\Repeater $repeater */
/** @var \Yaro\Jarboe\Table\Fields\AbstractField $field */
?>
<div data-repeater-item data-repeater-default-item class="repeater-item" style="display: none;">
    <div class="drag-handle {{ $repeater->isSortable() ? 'sortable' : '' }}"><span data-repeater-heading></span></div>

    <fieldset>
    @foreach ($repeater->getFields() as $field)

        @if ($rowsLeft - $field->getCol() < 0)
            <?php $rowsLeft = 12; ?>
            </div>
        @endif

        @if ($rowsLeft == 12)
            <div class="{{ $field->getCol() == 12 ? '' : 'row' }}">
        @endif



        @if ($rowsLeft - $field->getCol() >= 0)
            <?php $rowsLeft -= $field->getCol(); ?>
        @endif

        <section class="{{ $field->getCol() == 12 ? '' : 'col col-'. $field->getCol() }}">
            {!! $field->getEditFormView($model) !!}
        </section>

        @if (!$field->getCol())
            <?php $rowsLeft = 12; ?>
            </div>
        @endif
    @endforeach
    </fieldset>

    <div class="repeater-item-btn-container">
        <a href="javascript:void(0);" class="btn btn-default btn-xs" data-repeater-expand-toggle>
            <i class="fa fa-compress" aria-hidden="true" data-repeater-compress></i>
            <i class="fa fa-expand" aria-hidden="true" style="display: none;" data-repeater-expand></i>
        </a>
        <a href="javascript:void(0);" class="btn btn-default btn-xs" data-repeater-delete>{{ __('jarboe::fields.repeater.delete_item') }}</a>
    </div>
</div>
