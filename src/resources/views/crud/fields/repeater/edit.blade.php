<?php
/** @var \Yaro\Jarboe\Table\Fields\Repeater $repeater */
?>
<label class="label">
    {{ $repeater->title() }}
    @include('jarboe::crud.fields.repeater.inc.translatable_locales_selector')
</label>

@if ($repeater->isTranslatable())
    @foreach ($repeater->getLocales() as $locale => $title)
        <div class="locale-field locale-tab-{{ $locale }} locale-field-{{ $repeater->name() }} locale-field-{{ $repeater->name() }}-{{ $locale }}"
             style="{{ $repeater->isCurrentLocale($locale) ? '' : 'display:none;' }}">
            @include('jarboe::crud.fields.repeater.inc.item', [
                'repeater' => $repeater,
                'data' => $repeater->oldOrAttribute($model, $locale),
                'locale' => $locale,
                'errors' => $repeater->errors($errors->messages($repeater->name() .'.'. $locale .'.*')),
                'rowsLeft' => 12,
            ])
        </div>
    @endforeach
@else
    @include('jarboe::crud.fields.repeater.inc.item', [
        'repeater' => $repeater,
        'data' => $repeater->oldOrAttribute($model),
        'locale' => null,
        'errors' => $repeater->errors($errors->messages($repeater->name() .'.*')),
        'rowsLeft' => 12,
    ])
@endif


@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/jquery.repeater/jquery.repeater.js"></script>)
