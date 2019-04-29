
<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.wysiwyg.summernote.inc.translatable_locales_selector')
</label>

@if ($field->isTranslatable())
    @foreach ($field->getLocales() as $locale => $title)
        <div class="locale-field locale-tab-{{ $locale }} locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
             style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}">
            <div class="summernote-{{ $field->name() }}-{{ $locale }}"></div>
            <textarea class="summernote-{{ $field->name() }}-{{ $locale }}-content" name="{{ $field->name() }}[{{ $locale }}]" style="display: none;">{!! $field->getAttribute($model, $locale) !!}</textarea>
            @include('jarboe::crud.fields.wysiwyg.summernote.inc.error_messages', [
                'messages' => $errors->get($field->name() .'.'. $locale)
            ])
        </div>
        @include('jarboe::crud.fields.wysiwyg.summernote.inc.styles_and_scripts', compact('field', 'locale'))
    @endforeach
@else
    <div class="summernote-{{ $field->name() }}-default"></div>
    <textarea class="summernote-{{ $field->name() }}-default-content" name="{{ $field->name() }}" style="display: none;">{!! $field->getAttribute($model) !!}</textarea>
    @include('jarboe::crud.fields.wysiwyg.summernote.inc.error_messages', [
        'messages' => $errors->get($field->name())
    ])
    @include('jarboe::crud.fields.wysiwyg.summernote.inc.styles_and_scripts', [
        'field' => $field,
        'locale' => 'default',
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
