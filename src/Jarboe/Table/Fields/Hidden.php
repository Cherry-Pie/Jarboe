<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;

class Hidden extends AbstractField
{
    use Orderable;
    use Nullable;

    protected $col = 0;
    protected $hidden = [
        'list'   => true,
        'edit'   => false,
        'create' => false,
    ];

    public function value(Request $request)
    {
        return (string) parent::value($request);
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.hidden.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        return view('jarboe::crud.fields.hidden.edit', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.hidden.create', [
            'field' => $this,
        ]);
    }

    public function col(int $col)
    {
        $this->col = 0;

        return $this;
    }
}
