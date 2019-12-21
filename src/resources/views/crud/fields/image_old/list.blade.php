
@if ($field->isMultiple())
    @foreach (($field->getUrl($model) ?: []) as $url)
        @if (filter_var($url, FILTER_VALIDATE_URL))
            <div style="background-image: url('{{ $url }}')"
                 class="image-field"
                 data-lity
                 data-lity-target="{{ $url }}"></div>
        @elseif (preg_match('~^data:~', $url))
            <div style="background-image: url('{{ $url }}')"
                 class="image-field"
                 data-lity
                 data-lity-target="{{ $url }}"></div>
        @else
            {{ $url }}
        @endif
    @endforeach
@else
    @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
        <div style="background-image: url('{{ $field->getUrl($model) }}')"
             class="image-field"
             data-lity
             data-lity-target="{{ $field->getUrl($model) }}"></div>
    @elseif (preg_match('~^data:~', $field->getUrl($model)))
        <div style="background-image: url('{{ $field->getUrl($model) }}')"
             class="image-field"
             data-lity
             data-lity-target="{{ $field->getUrl($model) }}"></div>
    @else
        {{ $field->getUrl($model) }}
    @endif
@endif


@pushonce('style_files', <style>
    div.image-field {
        height: 50px;
        max-height: 50px;
        width: 50px;
        max-width: 50px;
        cursor: pointer;
        display: inline-block;
        background-size: cover;
    }
</style>)

