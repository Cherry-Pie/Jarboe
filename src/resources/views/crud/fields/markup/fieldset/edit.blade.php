<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Markup\FieldsetMarkup $field
 */
?>
<fieldset class="inner-fieldset">
    <legend>{{ $field->getLegend() }}</legend>
    @include('jarboe::crud.inc.edit.tab', [
        'item'     => $model,
        'fields'   => $field->getFields(),
        'rowsLeft' => 12,
    ])
</fieldset>
