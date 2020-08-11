<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Text $field
 */
?>

<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.text.inc.translatable_locales_selector')
</label>

@if ($field->isTranslatable())
    @foreach ($field->getLocales() as $locale => $title)
        <div class="locale-field locale-tab-{{ $locale }} locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
             style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}">
            <label class="input {{ $field->hasError($errors, $locale) ? 'state-error' : '' }}">
                @include('jarboe::crud.fields.text.inc.tooltip_icon')
                <input type="text"
                       @if ($field->hasMaxlength())
                           maxlength="{{ $field->getMaxlength() }}"
                       @endif
                       @if ($field->isMaskable())
                           data-mask="{{ $field->getMaskPattern() }}"
                           data-mask-placeholder="{{ $field->getMaskPlaceholder() }}"
                       @endif
                       value="{{ $field->oldOrDefault($locale) }}"
                       name="{{ $field->name() }}[{{ $locale }}]"
                       placeholder="{{ $field->getPlaceholder() }}">
                @include('jarboe::crud.fields.text.inc.tooltip_body')

                @include('jarboe::crud.fields.text.inc.error_messages', [
                    'messages' => $field->getErrors($errors, $locale),
                ])
            </label>
        </div>
    @endforeach
@else
    <label class="input {{ $field->hasError($errors) ? 'state-error' : '' }}">
        @include('jarboe::crud.fields.text.inc.tooltip_icon')
        <input type="text"
               @if ($field->hasMaxlength())
                   maxlength="{{ $field->getMaxlength() }}"
               @endif
               @if ($field->isMaskable())
                   data-mask="{{ $field->getMaskPattern() }}"
                   data-mask-placeholder="{{ $field->getMaskPlaceholder() }}"
               @endif
               value="{{ $field->oldOrDefault() }}"
               name="{{ $field->name() }}"
               placeholder="{{ $field->getPlaceholder() }}">
        @include('jarboe::crud.fields.text.inc.tooltip_body')

        @include('jarboe::crud.fields.text.inc.error_messages', [
            'messages' => $field->getErrors($errors),
        ])
    </label>
@endif

@push('scripts')
    <script>
        $('label.translation-{{ $field->name() }}-locale-label').on('click', function() {
            $('.locale-field-{{ $field->name() }}').hide();
            $('.locale-field-{{ $field->name() }}-'+ $(this).data('locale')).show();
        });
    </script>
@endpush
