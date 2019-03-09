<div class="wrapper-image-cropp wrapper-image-cropp-{{ $field->name() }}">
    @if ($field->isMultiple())
        @foreach (($field->getUrl($model) ?: []) as $url)
            @if (filter_var($url, FILTER_VALIDATE_URL))
                <div><img src="{{ $url }}" class="image-cropp-{{ $field->name() }}-{{ $loop->index }}" style="width: 100%;"></div>
            @elseif (preg_match('~^data:~', $url))
                <div><img src="{{ $url }}" class="image-cropp-{{ $field->name() }}-{{ $loop->index }}" style="width: 100%;"></div>
            @else
                <div>{{ $url }}</div>
            @endif
        @endforeach
    @else
        @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
            <div><img src="{{ $field->getUrl($model) }}" class="image-cropp-{{ $field->name() }}-0" style="width: 100%;"></div>
        @elseif (preg_match('~^data:~', $field->getUrl($model)))
            <div><img src="{{ $field->getUrl($model) }}" class="image-cropp-{{ $field->name() }}-0" style="width: 100%;"></div>
        @else
            <div>{{ $field->getUrl($model) }}</div>
        @endif
    @endif
</div>

@pushonce('style_files', <style>
    div.wrapper-image-cropp {
        width: 100%; margin-top: 12px;
    }
    div.wrapper-image-cropp > div {
        margin-bottom: 4px;
    }
</style>)
