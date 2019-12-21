@if ($field->isMultiple())

@else
    @if (filter_var($field->getCroppedOrOriginalUrl($model), FILTER_VALIDATE_URL))
        <div style="background-image: url('{{ $field->getCroppedOrOriginalUrl($model) }}')"
             class="image-field"
             data-lity
             data-lity-target="{{ $field->getCroppedOrOriginalUrl($model) }}"></div>
    @elseif (preg_match('~^data:~', $field->getCroppedOrOriginalUrl($model)))
        <div style="background-image: url('{{ $field->getCroppedOrOriginalUrl($model) }}')"
             class="image-field"
             data-lity
             data-lity-target="{{ $field->getCroppedOrOriginalUrl($model) }}"></div>
    @else
        {{ $field->getCroppedOrOriginalUrl($model) }}
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

