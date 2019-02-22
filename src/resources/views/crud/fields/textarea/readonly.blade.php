
<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.textarea.inc.translatable_locales_selector')
</label>

<label class="textarea {{ $field->isResizable() ? 'textarea-resizable' : '' }} {{ $field->isExpandable() ? 'textarea-expandable' : '' }} state-disabled">
    @if ($field->isTranslatable())
        @foreach ($field->getLocales() as $locale => $title)
            <div class="locale-field locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
                 style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}"
                 disabled>
                @include('jarboe::crud.fields.textarea.inc.tooltip_icon')
                <textarea rows="{{ $field->getRowsNum() }}"
                          class="custom-scroll"
                          disabled="disabled"
                          placeholder="{{ $field->getPlaceholder() }}">{{ $field->getAttribute($model, $locale) }}</textarea>
                @include('jarboe::crud.fields.textarea.inc.tooltip_body')
            </div>
        @endforeach
    @else
        @include('jarboe::crud.fields.textarea.inc.tooltip_icon')
        <textarea rows="{{ $field->getRowsNum() }}"
                  class="custom-scroll"
                  disabled="disabled"
                  placeholder="{{ $field->getPlaceholder() }}">{{ $field->getAttribute($model) }}</textarea>
        @include('jarboe::crud.fields.textarea.inc.tooltip_body')
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
