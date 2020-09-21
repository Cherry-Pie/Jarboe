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
            <div class="input {{ $field->hasError($errors, $locale) ? 'state-error' : '' }}">
                <textarea name="{{ $field->name() }}[{{ $locale }}]"
                          rows="{{ $field->getRowsNum() }}"
                          class="markdown-field custom-scroll">{!! $field->oldOrDefault($locale) !!}</textarea>

                @include('jarboe::crud.fields.markdown.inc.error_messages', [
                    'messages' => $field->getErrors($errors, $locale),
                ])
            </div>
        </div>
    @endforeach
@else
    <div class="textarea {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        <textarea name="{{ $field->name() }}"
                  rows="{{ $field->getRowsNum() }}"
                  class="markdown-field custom-scroll">{!! $field->oldOrDefault() !!}</textarea>
    </div>

    @include('jarboe::crud.fields.markdown.inc.error_messages', [
        'messages' => $field->getErrors($errors),
    ])
@endif


@include('jarboe::crud.fields.markdown.inc.scripts')
