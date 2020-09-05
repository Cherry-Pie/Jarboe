<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Markdown $field
 */
?>

<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.markdown.inc.translatable_locales_selector')
</label>

@if ($field->isTranslatable())
    @foreach ($field->getLocales() as $locale => $title)
        <div class="locale-field locale-tab-{{ $locale }} locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
             style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}">
            <label class="input {{ $field->hasError($errors, $locale) ? 'state-error' : '' }}">
                <textarea disabled
                          class="markdown-field custom-scroll">{!! $field->getAttribute($model, $locale) !!}</textarea>

                @include('jarboe::crud.fields.markdown.inc.error_messages', [
                    'messages' => $field->getErrors($errors, $locale),
                ])
            </label>
        </div>
    @endforeach
@else
    <div class="textarea {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        <textarea disabled class="markdown-field custom-scroll">{!! $field->getAttribute($model) !!}</textarea>
    </div>

    @include('jarboe::crud.fields.markdown.inc.error_messages', [
        'messages' => $field->getErrors($errors),
    ])
@endif


@include('jarboe::crud.fields.markdown.inc.scripts')
