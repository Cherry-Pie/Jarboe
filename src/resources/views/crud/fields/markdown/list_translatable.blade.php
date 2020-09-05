<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Markdown $field
 */
?>

@foreach ($field->getLocales() as $locale => $title)
    <div class="locale-field locale-field-{{ $locale }}" {!! $field->isCurrentLocale($locale) ? '' : 'style="display:none;"' !!}>
        @include('jarboe::crud.fields.markdown.list')
    </div>
@endforeach
