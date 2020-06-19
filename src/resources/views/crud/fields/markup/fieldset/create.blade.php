<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Markup\FieldsetMarkup $field
 */
?>
<fieldset>
    <legend>{{ $field->getLegend() }}</legend>
    @include('jarboe::crud.inc.create.tab', [
        'fields'   => $field->getFields(),
        'rowsLeft' => 12,
    ])
</fieldset>
