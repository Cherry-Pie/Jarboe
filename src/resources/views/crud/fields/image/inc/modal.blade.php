<!-- Modal -->
<div class="modal field-modal modal_{{ $field->name() }} fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                @if ($field->isCrop())
                        <div style="float:left">
                            <a href="javascript:void(0);"
                               class="btn btn-default cropper-init"
                               data-crop-ratio-width="{{ $field->getRatio('width') }}"
                               data-crop-ratio-height="{{ $field->getRatio('height') }}"
                               data-crop-width="{{ $field->getImage($model)->cropWidth() }}"
                               data-crop-height="{{ $field->getImage($model)->cropHeight() }}"
                               data-crop-x="{{ $field->getImage($model)->cropX() }}"
                               data-crop-y="{{ $field->getImage($model)->cropY() }}"
                            >Crop</a>
                            <a href="javascript:void(0);" class="btn btn-default cropper-destroy">Destroy</a>
                        </div>
                @endif

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
            </div>
            <div class="modal-body">

                <div class="row image-row-container">
                    <div class="col-md-12">
                        <div>
                            <img src="{{ $field->getImage($model ?? null)->originalSourceUrl('/vendor/jarboe/img/placeholder.png') }}" style="width: 100%;">
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
