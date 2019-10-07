@if ($field->isTranslatable())
    <div class="btn-group" data-toggle="buttons" style="float:right;">
        @foreach ($field->getLocales() as $locale => $title)
            <label class="btn btn-default btn-xs {{ $field->isCurrentLocale($locale) ? 'active' : '' }} translation-{{ $field->name() }}-locale-label translation-locale-label"
                   data-locale="{{ $locale }}">
                <input type="radio"
                       id="translation-{{ $field->name() }}-locale-{{ $locale }}">
                {{ $title }}
                @if ($errors->has($field->name() .'.'. $locale))
                <span style="color: #a90329;">
                    <i class="fa fa-exclamation-triangle"></i>
                </span>
                @endif
            </label>
        @endforeach
    </div>
@endif
