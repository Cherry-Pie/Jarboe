<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Translatable;

class Wysiwyg extends AbstractField
{
    use Orderable;
    use Translatable;

    const SUMMERNOTE = 'summernote';
    const TINYMCE = 'tinymce';

    protected $type = self::SUMMERNOTE;
    protected $allowedTypes = [
        self::SUMMERNOTE,
        self::TINYMCE,
    ];
    private $options = [];

    public function value(Request $request)
    {
        $value = parent::value($request);

        return is_array($value) ? $value : (string) $value;
    }

    public function type(string $type)
    {
        if (in_array($type, $this->allowedTypes)) {
            $this->type = $type;
        }

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        if ($this->options) {
            return $this->options;
        }

        switch ($this->getType()) {
            case self::SUMMERNOTE:
                return $this->summernoteDefaultOptions();
            case self::TINYMCE:
                return $this->tinymceDefaultOptions();
        }
    }

    public function getListView($model)
    {
        $template = 'list';
        if ($this->isTranslatable()) {
            $template .= '_translatable';
        }

        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.%s', $this->getType(), $template), [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.%s', $this->getType(), $template), [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.create', $this->getType()), [
            'field' => $this,
        ]);
    }

    private function summernoteDefaultOptions(): array
    {
        return [
            'height' => 200,
            'codemirror' => [
                'theme' => 'monokai',
            ],
            'toolbar' => [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['codeview', 'fullscreen']],
            ],
        ];
    }

    private function tinymceDefaultOptions(): array
    {
        return [
            'plugins' => 'code table lists autoresize link',
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontsizeselect | code | table | link',
        ];
    }
}
