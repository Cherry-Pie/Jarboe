<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.textarea.inc.translatable_locales_selector')
</label>

@if ($field->isTranslatable())
    @foreach ($field->getLocales() as $locale => $title)
        <div class="locale-field locale-tab-{{ $locale }} locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
             style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}">
            <label class="textarea {{ $field->isResizable() ? 'textarea-resizable' : '' }} {{ $field->isExpandable() ? 'textarea-expandable' : '' }} {{ $errors->has($field->name() .'.'. $locale) ? 'state-error' : '' }}">
                @include('jarboe::crud.fields.textarea.inc.tooltip_icon')
                <textarea rows="{{ $field->getRowsNum() }}"
                          name="{{ $field->name() }}[{{ $locale }}]"
                          class="custom-scroll"
                          @if ($field->hasMaxlength())
                            maxlength="{{ $field->getMaxlength() }}"
                          @endif
                          placeholder="{{ $field->getPlaceholder() }}">{{ $field->oldOrDefault($locale) }}</textarea>
                @include('jarboe::crud.fields.textarea.inc.tooltip_body')
            </label>
            @include('jarboe::crud.fields.textarea.inc.error_messages', [
                'messages' => $errors->get($field->name() .'.'. $locale)
            ])
        </div>
    @endforeach
@else
    <label class="textarea {{ $field->isResizable() ? 'textarea-resizable' : '' }} {{ $field->isExpandable() ? 'textarea-expandable' : '' }} {{ $errors->has($field->name()) ? 'state-error' : '' }}">
        @include('jarboe::crud.fields.textarea.inc.tooltip_icon')
        <textarea rows="{{ $field->getRowsNum() }}"
                  name="{{ $field->name() }}"
                  class="custom-scroll"
                  @if ($field->hasMaxlength())
                    maxlength="{{ $field->getMaxlength() }}"
                  @endif
                  placeholder="{{ $field->getPlaceholder() }}">{{ $field->oldOrDefault() }}</textarea>
        @include('jarboe::crud.fields.textarea.inc.tooltip_body')
    </label>
    @include('jarboe::crud.fields.textarea.inc.error_messages', [
        'messages' => $errors->get($field->name())
    ])
@endif

@push('scripts')
    <script>
        $('label.translation-{{ $field->name() }}-locale-label').on('click', function() {
            $('.locale-field-{{ $field->name() }}').hide();
            $('.locale-field-{{ $field->name() }}-'+ $(this).data('locale')).show();
        });
    </script>
@endpush
