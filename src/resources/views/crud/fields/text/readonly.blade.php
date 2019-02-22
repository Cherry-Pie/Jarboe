
<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.text.inc.translatable_locales_selector')
</label>
<label class="input state-disabled">

    @if ($field->isTranslatable())
        @foreach ($field->getLocales() as $locale => $title)
            <div class="locale-field locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
                 style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}"
                 disabled>
                @include('jarboe::crud.fields.text.inc.tooltip_icon')
                <input type="text"
                       value="{{ $field->getAttribute($model, $locale) }}"
                       placeholder="{{ $field->getPlaceholder() }}">
                @include('jarboe::crud.fields.text.inc.tooltip_body')
            </div>
        @endforeach
    @else
        @include('jarboe::crud.fields.text.inc.tooltip_icon')
        <input type="text"
               value="{{ $field->getAttribute($model) }}"
               placeholder="{{ $field->getPlaceholder() }}"
               disabled>
        @include('jarboe::crud.fields.text.inc.tooltip_body')
    @endif
</label>


@push('scripts')
    <script>
        $('label.translation-{{ $field->name() }}-locale-label').on('click', function() {
            $('.locale-field-{{ $field->name() }}').hide();
            $('.locale-field-{{ $field->name() }}-'+ $(this).data('locale')).show();
        });
    </script>
@endpush
