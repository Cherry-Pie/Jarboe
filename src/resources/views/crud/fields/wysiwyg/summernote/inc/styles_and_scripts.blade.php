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
      $(document).ready(function () {
        var $field_{{ $field->name() }}_{{ $locale }} = $('.summernote-{{ $field->name() }}-{{ $locale }}-content');
        $('.summernote-{{ $field->name() }}-{{ $locale }}').summernote({
          height: 200,
          codemirror: {
            theme: 'monokai'
          },
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['codeview', 'fullscreen']]
          ],
          callbacks: {
            onInit: function (e) {
              $('.summernote-{{ $field->name() }}-{{ $locale }}').summernote('code', $field_{{ $field->name() }}_{{ $locale }}.val());
              @if ($field->isReadonly())
                $('.summernote-{{ $field->name() }}-{{ $locale }}').summernote('disable');
              @endif
            },
            @if (!$field->isReadonly())
              onBlur: function (e) {
                $field_{{ $field->name() }}_{{ $locale }}.val($('.summernote-{{ $field->name() }}-{{ $locale }}').summernote('code'));
              },
            @endif
          }
        });
      });
    </script>
@endpush
