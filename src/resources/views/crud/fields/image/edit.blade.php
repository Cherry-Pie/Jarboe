<?php
/** @var \Yaro\Jarboe\Table\Fields\Image $field */
?>
<label class="label">{{ $field->title() }}</label>
<div class="input-block">

    @foreach ($field->getImagesPack($model) as $index => $image)
        @include('jarboe::crud.fields.image.inc.image_block', [
            'field' => $field,
            'image' => $image,
            'index' => $index,
        ])
    @endforeach


    @if ($field->isMultiple())
        <div class="img-input-footer">
            <a class="btn btn-default btn-sm add-image-btn" href="javascript:void(0);" data-template="#ident-{{ $field->name() }}">
                {{ __('jarboe::fields.image.add_image') }}
            </a>
        </div>
    @endif
</div>

@push('scripts')
    <script id="ident-{{ $field->name() }}" type="text/template">
        @include('jarboe::crud.fields.image.inc.image_block', [
            'field' => $field,
            'image' => $field->getImage(),
            'index' => '__index_placeholder__'
        ])
    </script>
@endpush


@pushonce('style_files', <link href="/vendor/jarboe/js/plugin/jquery-cropper/cropper.min.css" rel="stylesheet">)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/jquery-cropper/cropper.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/jquery-cropper/jquery-cropper.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/knob/jquery.knob.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/colorpicker/bootstrap-colorpicker.min.js"></script>)

@push('scripts')
<script>
    Jarboe.add('{{ $field->name() }}', function() {
        $('.add-image-btn').unbind('click').on('click', function() {
            const $parentElem = $(this).parent();

            const name = $parentElem.prev().find('.input-file .upload-input').get(0).name;
            let matches = name.match(/^(.+)\[([^\]]+)\]\[[^\]]+\]$/);
            const toPrefix = matches[1];
            const index = matches[2];
            const nextIndex = parseInt(index) + 1;


            const html = $($(this).data('template')).html();
            const $imageBlock = $(html);
            matches = $imageBlock
                .find('.input-file .upload-input')
                .get(0)
                .name.match(/^(.+)\[([^\]]+)\]\[[^\]]+\]$/);
            const fromPrefix = matches[1];

            $imageBlock.html(
                $imageBlock.html()
                    .replace(/__index_placeholder__/g, nextIndex)
                    .replace(new RegExp(jarboe.escapeRegExp(fromPrefix), 'g'), toPrefix)
            );
            $parentElem.before($imageBlock);

            Jarboe.init('{{ $field->name() }}');
        });

        $('.image-remove').unbind('click').on('click', function() {
            let $btn = $(this);

            jarboe.confirmBox({
                title: "{{ __('jarboe::fields.image.confirmbox_delete_image_title') }}",
                content: "{{ __('jarboe::fields.image.confirmbox_delete_image_description') }}",
                buttons: {
                    '{{ __('jarboe::fields.image.confirmbox_delete_image_yes') }}': function() {
                        $btn.addClass('disabled');

                        let $imageLeftPart = $btn.closest('.image-block').find('.image-left-part');
                        $imageLeftPart.find('.cropper-modal-open').addClass('disabled');
                        let $imageRowContainer = $imageLeftPart.find('.image-row-container-preview');
                        $imageRowContainer.removeAttr('style');
                        $imageRowContainer.find('img')
                            .removeAttr('style')
                            .attr('src', '/vendor/jarboe/img/placeholder.png');

                        $imageLeftPart.parent().find('.original-source').val('');

                        jarboe.smallToast({
                            title: "{{ __('jarboe::fields.image.toast_image_deleted_title') }}",
                            content: "{{ __('jarboe::fields.image.toast_image_deleted_description') }}",
                            color: "#659265",
                            iconSmall: "fa fa-check fa-2x fadeInRight animated",
                            timeout: 4000
                        });
                    },
                    '{{ __('jarboe::fields.image.confirmbox_delete_image_no') }}': null,
                }
            });
        });

        $('a.cropper-modal-open').unbind('click').on('click', function() {
            var $wrapper = $(this).closest('.image-block');
            var $modal = $wrapper.find('.field-modal');

            $modal.find('.cropper-init').on('click', function() {
                var $btn = $(this);
                $btn.addClass('disabled');
                $modal.find('.cropper-destroy').removeClass('disabled');

                $wrapper.find('.image-left-part .image-row-container-preview').css('overflow', 'hidden');
                var options = {
                    preview:  $wrapper.find('.image-left-part .image-row-container-preview, .field-modal .image-row-container-preview'),
                    viewMode: 2,
                    data: {
                        width: $btn.data('crop-width'),
                        height: $btn.data('crop-height'),
                        x: $btn.data('crop-x'),
                        y: $btn.data('crop-y'),
                        rotate: $btn.data('crop-rotate'),
                    },
                    crop: function(event) {
                        $btn.data('crop-width', event.detail.width);
                        $btn.data('crop-height', event.detail.height);
                        $btn.data('crop-x', event.detail.x);
                        $btn.data('crop-y', event.detail.y);

                        $wrapper.find('.cropped-source').val('');
                        $wrapper.find('.crop-width').val(event.detail.width);
                        $wrapper.find('.crop-height').val(event.detail.height);
                        $wrapper.find('.crop-x').val(event.detail.x);
                        $wrapper.find('.crop-y').val(event.detail.y);
                        $wrapper.find('.crop-rotate').val(event.detail.rotate);
                    },
                };
                if ($(this).data('crop-ratio-width') && $(this).data('crop-ratio-height')) {
                    options.aspectRatio = parseInt($(this).data('crop-ratio-width')) / parseInt($(this).data('crop-ratio-height'));
                }
                if ($btn.data('crop-width') === '' && $btn.data('crop-height') === '' && $btn.data('crop-x') === '' && $btn.data('crop-y') === '') {
                    delete options.data;
                }
                $modal.find('.image-row-container img').cropper(options);

                knobEnabled = true;
                knobPreviousValue = $btn.data('crop-rotate');
            });

            $modal.find('.cropper-destroy').on('click', function() {
                $(this).addClass('disabled');
                let $btn = $modal.find('.cropper-init');
                $btn.removeClass('disabled');

                $modal.find('.knob').val($btn.data('crop-rotate')).trigger('change');
                $modal.find('.image-row-container img').cropper('destroy');
                $wrapper.find('.crop-width').val(null);
                $wrapper.find('.crop-height').val(null);
                $wrapper.find('.crop-x').val(null);
                $wrapper.find('.crop-y').val(null);
                $wrapper.find('.crop-rotate').val(null);
                $wrapper.find('.crop-rotate-background').val(null);

                knobEnabled = false;
                knobPreviousValue = $btn.data('crop-rotate');
            });

            let knobEnabled = false;
            let knobPreviousValue = parseInt($modal.find('.cropper-init').data('crop-rotate')) || 0;
            $modal.find('.knob').knob({
                change: function (value) {
                    let cropper = $modal.find('.image-row-container img').data('cropper');
                    if (cropper) {
                        cropper.rotateTo(value);
                    }
                },
                release: function (value) {
                    let cropper = $modal.find('.image-row-container img').data('cropper');
                    if (cropper) {
                        cropper.rotateTo(value);
                    }
                },
                draw: function () {
                    if (knobPreviousValue === this.v) {
                        return;
                    }

                    if (!knobEnabled && knobPreviousValue !== null) {
                        $modal.find('.knob').val(knobPreviousValue).trigger("change");
                        return;
                    }

                    knobPreviousValue = this.v;
                },
            });

            $modal.find('input.colorpicker')
                .colorpicker()
                .on('changeColor', function(event) {
                    let color = event.color.toRGB();
                    color = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ',' + color.a + ')';
                    $wrapper.find('.crop-rotate-background').val(color);

                    $wrapper.find('.image-row-container-preview').css('background-color', color);
                    $(this).parent().find('.icon-color').css('background-color', color);
                });

            $modal.modal('show');
        });

        $('.image-block .input-file input.upload-input').unbind('change').on('change', function() {
            const ctx = this;
            const $wrapper = $(ctx).closest('.image-block');
            const $modal = $wrapper.find('.field-modal');
            const $btn = $modal.find('.cropper-init');

            $(ctx).parent().next().val(
                ctx.value.split('\\').pop().split('/').pop()
            );

            const files = ctx.files;
            if (!files || !files.length) {
                return;
            }

            $.each(files, function(index, file) {
                if (!/^image\/\w+$/.test(file.type)) {
                    return;
                }

                let uploadedImageURL = URL.createObjectURL(file);
                $modal.find('.image-row-container img').cropper('destroy').attr('src', uploadedImageURL);
                $modal.find('.image-row-container-preview img').attr('src', uploadedImageURL);
                $wrapper.find('.image-left-part img').attr('src', uploadedImageURL);
            });

            $wrapper.find('.cropper-destroy').trigger('click');

            $btn.data('crop-width', '');
            $btn.data('crop-height', '');
            $btn.data('crop-x', '');
            $btn.data('crop-y', '');
            $btn.data('crop-rotate', '');
            $modal.find('.knob').val('0');
            $modal.find('input.colorpicker').val('');
            $modal.find('input.colorpicker').colorpicker('setValue', 'rgba(255, 255, 255 ,0)');

            $wrapper.find('.image-row-container-preview').css('width', 'auto').css('height', 'auto');

            $wrapper.find('a.cropper-modal-open').removeClass('disabled').trigger('click');
        });
    }, '{{ $locale ?? 'default' }}');

    $(document).ready(function () {
        Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
    });
</script>
@endpush
