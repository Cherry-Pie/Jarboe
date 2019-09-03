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

    protected $type = self::SUMMERNOTE;
    protected $allowedTypes = [
        self::SUMMERNOTE,
    ];

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

    public function getListValue($model)
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

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.%s', $this->getType(), $template), [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.create', $this->getType()), [
            'field' => $this,
        ]);
    }
}
