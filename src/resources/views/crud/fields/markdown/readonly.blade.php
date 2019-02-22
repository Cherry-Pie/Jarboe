
<label class="label">{{ $field->title() }}</label>

<textarea class="mymarkdown-{{ $field->name() }} custom-scroll" disabled="disabled">{!! $model->{$field->name()} !!}</textarea>


@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/markdown/markdown.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/markdown/to-markdown.min.js"></script>)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/markdown/bootstrap-markdown.min.js"></script>)

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".mymarkdown-{{ $field->name() }}").markdown({
                autofocus: false,
            });
        })
    </script>
@endpush

@push('styles')
    <style>
        .md-input {
            box-sizing: border-box;
        }
        .md-input .md-editor>.md-preview {
            box-sizing: border-box;
        }
        .md-editor.md-fullscreen-mode {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .md-editor .md-input {
            max-height: 420px;
        }
        .md-editor.md-fullscreen-mode .md-input {
            max-height: initial;
        }
        .md-editor>.md-preview {
            width: auto !important;
        }
    </style>
@endpush