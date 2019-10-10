@if ($repeater->isTranslatable())
    <div class="btn-group" data-toggle="buttons" style="float:right;">
        @foreach ($repeater->getLocales() as $locale => $title)
            <label class="btn btn-default btn-xs {{ $repeater->isCurrentLocale($locale) ? 'active' : '' }} translation-{{ $repeater->name() }}-locale-label translation-locale-label"
                   data-locale="{{ $locale }}">
                <input type="radio"
                       id="translation-{{ $repeater->name() }}-locale-{{ $locale }}">
                {{ $title }}
                @if ($errors->has($repeater->name() .'.'. $locale))
                <span style="color: #a90329;">
                    <i class="fa fa-exclamation-triangle"></i>
                </span>
                @endif
            </label>
        @endforeach
    </div>
@endif
