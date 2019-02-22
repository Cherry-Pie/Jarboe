
<label class="label">{{ $field->title() }}</label>

<div class="input input-file {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <span class="button">
        <input type="file"
               name="{{ $field->name() }}{{ $field->isMultiple() ? '[]' : '' }}"
               {{ $field->isMultiple() ? 'multiple' : '' }}
               onchange="onChange{{ $field->name() }}(this)"
               accept="image/*">
        Browse
    </span>
    <input type="text" placeholder="{{ $field->getPlaceholder() }}" readonly="readonly">

    @if ($field->isCrop() && !$field->isMultiple())
        <input type="hidden" name="__{{ $field->name() }}_cropvalues[width]" class="{{ $field->name() }}_cropvalues_width">
        <input type="hidden" name="__{{ $field->name() }}_cropvalues[height]" class="{{ $field->name() }}_cropvalues_height">
        <input type="hidden" name="__{{ $field->name() }}_cropvalues[x]" class="{{ $field->name() }}_cropvalues_x">
        <input type="hidden" name="__{{ $field->name() }}_cropvalues[y]" class="{{ $field->name() }}_cropvalues_y">
    @endif
</div>

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


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach



@push('styles')
    @if ($field->isCrop() && !$field->isMultiple())
        @pushonce('style_files', <link href="/vendor/jarboe/js/plugin/jquery-cropper/cropper.min.css" rel="stylesheet">)
    @endif
@endpush

@push('scripts')
    @if ($field->isCrop() && !$field->isMultiple())
        @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/jquery-cropper/cropper.min.js"></script>)
        @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/jquery-cropper/jquery-cropper.min.js"></script>)
    @endif

    <script>
        function onChange{{ $field->name() }}(ctx) {
            $(ctx).parent().next().val(ctx.value);

            var files = ctx.files;
            if (!files || !files.length) {
                return;
            }
            var file = files[0];

            if (!/^image\/\w+$/.test(file.type)) {
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                $('.image-cropp-{{ $field->name() }}').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);

            @if ($field->isCrop() && !$field->isMultiple())
            var options{{ $field->name() }} = {
                @if ($field->getRatio('width') && $field->getRatio('height'))
                    aspectRatio: {{ $field->getRatio('width') }} / {{ $field->getRatio('height') }},
                @endif
                viewMode: 2,
                crop: function(event) {
                    $('.{{ $field->name() }}_cropvalues_width').val(event.detail.width);
                    $('.{{ $field->name() }}_cropvalues_height').val(event.detail.height);
                    $('.{{ $field->name() }}_cropvalues_x').val(event.detail.x);
                    $('.{{ $field->name() }}_cropvalues_y').val(event.detail.y);
                }
            };
            $('.image-cropp-{{ $field->name() }}').cropper(options{{ $field->name() }});

            if ($('.image-cropp-{{ $field->name() }}').data('cropper')) {
                var URL = window.URL || window.webkitURL;
                $('.image-cropp-{{ $field->name() }}').cropper('destroy').attr('src', URL.createObjectURL(file)).cropper(options{{ $field->name() }});
            }
            @endif
        }
    </script>
@endpush