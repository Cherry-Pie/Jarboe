<?php
/** @var \Yaro\Jarboe\Table\Fields\File $field */
?>
<ol class="file-field-dd-list no-padding">
    @foreach ($field->getPaths($model) as $path)
        <li class="dd-item">
            <div class="dd3-content" style="cursor: initial;">
                <div class="document-item" filetype="{{ pathinfo($path, PATHINFO_EXTENSION) }}">
                    <span class="fileCorner"></span>
                    <span class="elips">{{ $field->formUrl($path) }}</span>
                    <span class="pull-right clipspan">
                        <a href="javascript:void(0);"
                           class="btn btn-labeled btn-default clipclip"
                           data-clipboard-text="{{ $field->formUrl($path) }}">
                            {{ __('jarboe::fields.clipboard_copy') }}
                        </a>
                    </span>
                </div>
            </div>
        </li>
    @endforeach
</ol>
