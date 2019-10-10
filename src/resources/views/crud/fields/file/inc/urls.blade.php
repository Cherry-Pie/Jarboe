<div style="width: 100%; margin-top: 12px;">
    @if ($field->isMultiple())
        <ul style="padding-left: 15px;">
            @foreach (($field->getUrl($model) ?: []) as $url)
                <li>
                    @if (filter_var($url, FILTER_VALIDATE_URL))
                        <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                    @else
                        {{ $url }}
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
            <a href="{{ $field->getUrl($model) }}" target="_blank">{{ $model->{$field->name()} }}</a>
        @else
            {{ $model->{$field->name()} }}
        @endif
    @endif
</div>
