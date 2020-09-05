<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Markdown $field
 */
?>

{{ mb_strimwidth(strip_tags($field->getAttribute($model, $locale ?? null)), 0, 50, '...') }}
