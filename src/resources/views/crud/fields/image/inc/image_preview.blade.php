@if ($field->isCrop())
    @pushonce('style_files', <link href="/vendor/jarboe/js/plugin/jquery-cropper/cropper.min.css" rel="stylesheet">)
@endif

@push('scripts')
    @if ($field->isCrop())
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

            var $imageWrapper = $('.wrapper-image-cropp-{{ $field->name() }}');
            $imageWrapper.html('');

            $.each(files, function(index, file) {
                if (!/^image\/\w+$/.test(file.type)) {
                    return;
                }

                $imageWrapper.append(
                    '<div>' +
                    '<img class="image-cropp-{{ $field->name() }}-'+ index +'" style="width: 100%;" />' +
                        @if ($field->isCrop())
                            '<input type="hidden" name="__{{ $field->name() }}_cropvalues['+ index +'][width]" class="{{ $field->name() }}_cropvalues_width_'+ index +'" />' +
                    '<input type="hidden" name="__{{ $field->name() }}_cropvalues['+ index +'][height]" class="{{ $field->name() }}_cropvalues_height_'+ index +'" />' +
                    '<input type="hidden" name="__{{ $field->name() }}_cropvalues['+ index +'][x]" class="{{ $field->name() }}_cropvalues_x_'+ index +'" />' +
                    '<input type="hidden" name="__{{ $field->name() }}_cropvalues['+ index +'][y]" class="{{ $field->name() }}_cropvalues_y_'+ index +'" />' +
                        @endif
                            '</div>'
                );
                $imageWrapper.append(document.createElement('hr'));

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.image-cropp-{{ $field->name() }}-'+ index).attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                        @if ($field->isCrop())
                var options{{ $field->name() }} = {
                            @if ($field->getRatio('width') && $field->getRatio('height'))
                            aspectRatio: {{ $field->getRatio('width') }} / {{ $field->getRatio('height') }},
                            @endif
                            viewMode: 2,
                        crop: function(event) {
                            $('.{{ $field->name() }}_cropvalues_width_'+ index).val(event.detail.width);
                            $('.{{ $field->name() }}_cropvalues_height_'+ index).val(event.detail.height);
                            $('.{{ $field->name() }}_cropvalues_x_'+ index).val(event.detail.x);
                            $('.{{ $field->name() }}_cropvalues_y_'+ index).val(event.detail.y);
                        }
                    };
                $('.image-cropp-{{ $field->name() }}-'+ index).cropper(options{{ $field->name() }});

                if ($('.image-cropp-{{ $field->name() }}-'+ index).data('cropper')) {
                    var URL = window.URL || window.webkitURL;
                    $('.image-cropp-{{ $field->name() }}-'+ index).cropper('destroy').attr('src', URL.createObjectURL(file)).cropper(options{{ $field->name() }});
                }
                @endif
            });
        }
    </script>
@endpush
