<?php
/** @var \Yaro\Jarboe\Table\Fields\Repeater $repeater */
?>
@foreach(($repeater->getAttribute($model, $locale ?? null) ?: []) as $item)
    {{ $item[$repeater->getHeadingName()] ?? null }}
    <br>
@endforeach
