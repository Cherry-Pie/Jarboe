<div class="wrapper-image-cropp wrapper-image-cropp-{{ $field->name() }}">
    @if ($field->isMultiple())
        @foreach (($field->getUrl($model) ?: []) as $url)
            @if (filter_var($url, FILTER_VALIDATE_URL))
                <div class="img-box"><img src="{{ $url }}" class="image-cropp-{{ $field->name() }}-{{ $loop->index }}" style="width: 100%;"></div>
            @elseif (preg_match('~^data:~', $url))
                <div class="img-box"><img src="{{ $url }}" class="image-cropp-{{ $field->name() }}-{{ $loop->index }}" style="width: 100%;"></div>
            @else
                <div>{{ $url }}</div>
            @endif
        @endforeach
    @else
        @if (filter_var($field->getUrl($model), FILTER_VALIDATE_URL))
            <div class="img-box"><img src="{{ $field->getUrl($model) }}" class="image-cropp-{{ $field->name() }}-0" style="width: 100%;"></div>
        @elseif (preg_match('~^data:~', $field->getUrl($model)))
            <div class="img-box"><img src="{{ $field->getUrl($model) }}" class="image-cropp-{{ $field->name() }}-0" style="width: 100%;"></div>
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

    div.wrapper-image-cropp > div.img-box {
        max-height: 125px;
        overflow: hidden;
        -webkit-transition: max-height 1s;
        -moz-transition: max-height 1s;
        transition: max-height 1s;
        cursor: pointer;
    }
    div.wrapper-image-cropp > div.img-box.expanded {
        max-height: 999px;
    }
</style>)

@pushonce('script_files', <script>
    $(document).on('click', 'div.wrapper-image-cropp div.img-box', function() {
        $(this).toggleClass('expanded');
    });
</script>)
