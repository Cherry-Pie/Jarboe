
@if ($field->isMultiple())
    @foreach (($field->getUrl($model) ?: []) as $url)
        @if (filter_var($url, FILTER_VALIDATE_URL))
            <a href="{{ $url }}" target="_blank">{{ $url }}</a>
        @else
            {{ $url }}
        @endif
        <hr style="margin: 2px 0;">
    @endforeach
@else
    @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
        <a href="{{ $field->getUrl($model) }}" target="_blank">{{ $model->{$field->name()} }}</a>
    @else
        {{ $model->{$field->name()} }}
    @endif
@endif