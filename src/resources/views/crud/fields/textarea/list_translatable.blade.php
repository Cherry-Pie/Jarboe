
@foreach ($field->getLocales() as $locale => $title)
    <div class="locale-field locale-field-{{ $locale }}" {!! $field->isCurrentLocale($locale) ? '' : 'style="display:none;"' !!}>
        @include('jarboe::crud.fields.textarea.list')
    </div>
@endforeach
