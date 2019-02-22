<?php

namespace Yaro\Jarboe\Table\Fields;


use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;

class Wysiwyg extends AbstractField
{
    use Orderable;

    const SUMMERNOTE = 'summernote';

    protected $type = self::SUMMERNOTE;
    protected $allowedTypes = [
        self::SUMMERNOTE,
    ];

    public function value(Request $request)
    {
        return (string) parent::value($request);
    }

    public function type($type = null)
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
        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.list', $this->getType()), [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.%s', $this->getType(), $template), [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view(sprintf('jarboe::crud.fields.wysiwyg.%s.create', $this->getType()), [
            'field' => $this,
        ])->render();
    }

}