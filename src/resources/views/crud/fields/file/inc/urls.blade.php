<?php
/** @var \Yaro\Jarboe\Table\Fields\File $field */
?>
<div class="dd" style="margin-top: 4px;">
    <ol class="file-field-dd-list">
        @foreach ($field->getPaths($model) as $path)
            <li class="dd-item">
                <div class="dd3-content">
                    <div class="document-item" filetype="{{ pathinfo($path, PATHINFO_EXTENSION) }}">
                        <span class="fileCorner"></span>
                        @if (!$field->isReadonly())
                            <input name="{{ $field->name() }}[paths][]" type="hidden" value="{{ $path }}">
                        @endif
                        <span class="elips">{{ $field->formUrl($path) }}</span>
                        <span class="pull-right" style="line-height: 0px;position: absolute;top: 0;right: 0;">
                            <a href="javascript:void(0);"
                               class="btn btn-labeled btn-default clipclip"
                               style="margin-right: 4px; position: initial;"
                               data-clipboard-text="{{ $field->formUrl($path) }}">
                                {{ __('jarboe::fields.clipboard_copy') }}
                            </a>
                            @if (!$field->isReadonly())
                                <a href="javascript:void(0);"
                                   class="btn btn-default btn-xs delete-file-btn"
                                   style="margin-right: 4px;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            @endif
                        </span>
                    </div>
                </div>
            </li>
        @endforeach
    </ol>
</div>
