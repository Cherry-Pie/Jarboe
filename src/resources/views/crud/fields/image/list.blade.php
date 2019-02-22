
@if ($field->isMultiple())
    @foreach (($field->getUrl($model) ?: []) as $url)
        @if (filter_var($url, FILTER_VALIDATE_URL))
            <a href="{{ $url }}" target="_blank">
                <img src="{{ $url }}" style="height: 50px; max-height: 50px;">
            </a>
        @elseif (preg_match('~^data:~', $url))
            <img src="{{ $url }}" style="height: 50px; max-height: 50px;">
        @else
            {{ $url }}
        @endif
        <hr style="margin: 2px 0;">
    @endforeach
@else
    @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
        <a href="{{ $field->getUrl($model) }}" target="_blank">
            <img src="{{ $field->getUrl($model) }}" style="height: 50px; max-height: 50px;">
        </a>
    @elseif (preg_match('~^data:~', $field->getUrl($model)))
        <img src="{{ $field->getUrl($model) }}" style="height: 50px; max-height: 50px;">
    @else
        {{ $field->getUrl($model) }}
    @endif
@endif
