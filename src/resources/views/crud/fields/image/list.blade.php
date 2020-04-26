<?php
/** @var \Yaro\Jarboe\Table\Fields\Image $field */
/** @var \Yaro\Jarboe\Pack\Image $image */
?>

@foreach ($field->getImagesPack($model) as $image)
    @if (filter_var($image->croppedOrOriginalSourceUrl(), FILTER_VALIDATE_URL))
        <div style="background-image: url('{{ $image->croppedOrOriginalSourceUrl() }}')"
             class="image-field"
             data-lity
             data-lity-target="{{ $image->croppedOrOriginalSourceUrl() }}"></div>
    @elseif (preg_match('~^data:~', $image->croppedOrOriginalSourceUrl()))
        <div style="background-image: url('{{ $image->croppedOrOriginalSourceUrl() }}')"
             class="image-field"
             data-lity
             data-lity-target="{{ $image->croppedOrOriginalSourceUrl() }}"></div>
    @else
        {{ $image->croppedOrOriginalSourceUrl() }}
        <br>
    @endif
@endforeach
