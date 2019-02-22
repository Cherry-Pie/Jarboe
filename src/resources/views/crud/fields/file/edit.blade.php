
<label class="label">{{ $field->title() }}</label>

<div class="input input-file {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <span class="button">
        <input type="file" name="{{ $field->name() }}{{ $field->isMultiple() ? '[]' : '' }}" {{ $field->isMultiple() ? 'multiple' : '' }} onchange="$(this).parent().next().val(this.value)">
        Browse
    </span>
    <input type="text" placeholder="{{ $field->getPlaceholder() }}" readonly="readonly">
</div>

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

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach



