<label>
<label class="label">
    {{ $field->title() }}
    @include('jarboe::crud.fields.wysiwyg.tinymce.inc.translatable_locales_selector')
</label>

@if ($field->isTranslatable())
    @foreach ($field->getLocales() as $locale => $title)
        <div class="locale-field locale-tab-{{ $locale }} locale-field-{{ $field->name() }} locale-field-{{ $field->name() }}-{{ $locale }}"
             style="{{ $field->isCurrentLocale($locale) ? '' : 'display:none;' }}">
            <textarea class="tinymce-{{ $field->name() }}-{{ $locale }}" style="visibility: hidden;">{!! $model->{$field->name()} !!}</textarea>
        </div>
        @include('jarboe::crud.fields.wysiwyg.tinymce.inc.styles_and_scripts', compact('field', 'locale'))
    @endforeach
@else
    <textarea class="tinymce-{{ $field->name() }}-default" style="visibility: hidden;">{!! $model->{$field->name()} !!}</textarea>
    @include('jarboe::crud.fields.wysiwyg.tinymce.inc.styles_and_scripts', [
        'field' => $field,
        'locale' => 'default',
    ])
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
