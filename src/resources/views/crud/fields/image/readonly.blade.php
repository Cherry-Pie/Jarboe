
<label class="label">{{ $field->title() }}</label>

<label class="input input-file state-disabled">
    <span class="button disabled">
        Browse
    </span>
    <input type="text" disabled="disabled" accept="image/*">
</label>

<div style="width: 100%; margin-top: 12px;">
    @if ($field->isMultiple())
        @foreach (($field->getUrl($model) ?: []) as $url)
            @if (filter_var($url, FILTER_VALIDATE_URL))
                <img src="{{ $url }}" class="image-cropp-{{ $field->name() }}" style="width: 100%;">
            @elseif (preg_match('~^data:~', $url))
                <img src="{{ $url }}" class="image-cropp-{{ $field->name() }}" style="width: 100%;">
            @else
                {{ $url }}
            @endif
            <hr>
        @endforeach
    @else
        @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
            <img src="{{ $field->getUrl($model) }}" class="image-cropp-{{ $field->name() }}" style="width: 100%;">
        @elseif (preg_match('~^data:~', $field->getUrl($model)))
            <img src="{{ $field->getUrl($model) }}" class="image-cropp-{{ $field->name() }}" style="width: 100%;">
        @else
            {{ $model->{$field->name()} }}
        @endif
    @endif
</div>