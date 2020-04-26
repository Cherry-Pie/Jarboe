@pushonce('style_files', <link rel="stylesheet" type="text/css" href="/vendor/jarboe/js/plugin/codemirror/3.20.0/codemirror.css">)
@pushonce('style_files', <link rel="stylesheet" type="text/css" href="/vendor/jarboe/js/plugin/codemirror/3.20.0/theme/monokai.css">)

@pushonce('style_files',
<style>
    div.note-editor .modal-dialog {
        margin: 30px auto;
    }

    div.note-editor .modal-dialog .modal-header {
        padding: 15px;
    }

    div.note-editor .modal-dialog .modal-body {
        padding: 20px;
    }

    div.note-editor .modal-dialog .modal-footer {
        padding: 20px;
    }

    div.note-editor .modal-dialog .form-group {
        margin-bottom: 15px;
    }

    div.note-editor .modal-dialog .form-group input {
        box-sizing: border-box;
        padding: 6px 12px;
    }

    div.note-editor .modal-dialog .form-group label {
        margin: 0 0 5px 0;
    }

    div.note-editor .modal-dialog .btn {
        padding: 6px 12px;
    }

    div.note-editor .modal-dialog div.checkbox {
        padding: initial;
        line-height: initial;
    }

    div.note-editor .modal-dialog div.checkbox input {
        position: initial;
    }
</style>)

@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/codemirror/3.20.0/codemirror.js"></script>)
@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/codemirror/3.20.0/mode/xml/xml.js"></script>)
@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/codemirror/2.36.0/formatting.js"></script>)
@pushonce('script_files', <script type="text/javascript" src="/vendor/jarboe/js/plugin/summernote/summernote.min.js"></script>)

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.wysiwyg-summernote-field').each(function() {
                const $this = $(this);
                let options = $this.data('options');
                if ($this.is(':disabled')) {
                    options.readonly = true;
                }
                $this.summernote(options);
            });
        }, '{{ $locale }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale }}');
        });
    </script>
@endpush
