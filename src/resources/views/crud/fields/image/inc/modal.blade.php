<?php
/** @var \Yaro\Jarboe\Table\Fields\Image $field */
/** @var \Yaro\Jarboe\Pack\Image $image */
?>
<!-- Modal -->
<div class="modal field-modal modal_{{ $field->name() }} fade image-full-modal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row" style="margin: 0;">
                    <div class="col-md-9 padding-5">
                        <div class="image-row-container">
                            <img src="{{ $image->originalSourceUrl('/vendor/jarboe/img/placeholder.png') }}" style="width: 100%;">
                        </div>
                    </div>
                    <div class="col-md-3 padding-5">
                        <div class="clearfix">
                            @if($field->isCrop())
                                <a href="javascript:void(0);"
                                   class="btn btn-default cropper-init"
                                   data-crop-ratio-width="{{ $field->getRatio('width') }}"
                                   data-crop-ratio-height="{{ $field->getRatio('height') }}"
                                   data-crop-width="{{ $image->cropWidth() ?? '' }}"
                                   data-crop-height="{{ $image->cropHeight() ?? '' }}"
                                   data-crop-x="{{ $image->cropX() ?? '' }}"
                                   data-crop-y="{{ $image->cropY() ?? '' }}"
                                   data-crop-rotate="{{ $image->cropRotate() ?? '0' }}"
                                >{{ __('jarboe::fields.image.crop_init') }}</a>
                                <a href="javascript:void(0);"
                                   class="btn btn-default disabled cropper-destroy">{{ __('jarboe::fields.image.crop_destroy') }}</a>
                            @endif

                            <a href="javascript:void(0);"
                               data-dismiss="modal"
                               aria-hidden="true"
                               class="btn btn-primary pull-right close-cropper-modal">{{ __('jarboe::fields.image.modal_close') }}</a>
                        </div>

                        <hr style="margin: 20px 0;">

                        <div class="image-row-container-preview" style="overflow: hidden; background-color: {{ $image->cropRotateBackground() }};">
                            <img src="{{ $image->croppedOrOriginalSourceUrl('/vendor/jarboe/img/placeholder.png') }}" style="width: 100%;">
                        </div>

                        <hr style="margin: 20px 0;">

                        @if($field->isCrop())
                            <div class="row no-margin">
                                <div class="col-md-6 align-center">
                                    <input class="knob"
                                           data-width="150"
                                           data-height="150"
                                           data-cursor="true"
                                           data-fgColor="#222222"
                                           data-min="0"
                                           data-max="359"
                                           data-thickness=".3"
                                           value="{{ $image->cropRotate() ?? '0' }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="row no-margin">
                                        <div class="col-md-12">
                                            <label class="label">{{ __('jarboe::fields.image.background_color_description') }}</label>
                                            <label class="input">
                                                <i class="icon-append icon-color" style="background-color: {{ $image->cropRotateBackground() }};">&nbsp;&nbsp;&nbsp;&nbsp;</i>
                                                <input class="colorpicker" type="text" value="{{ $image->cropRotateBackground() }}" data-color-format="rgba">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
