<?php

namespace Yaro\Jarboe\Table;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\AbstractField;

class ModelRepository
{
    /**
     * @var CRUD
     */
    private $crud;
    private $filter;
    private $perPage = 20;
    private $defaultOrder = [
        'column'    => false,
        'direction' => false,
    ];

    public function __construct(CRUD $crud)
    {
        $this->crud = $crud;
    }

    public function get()
    {
        $model = $this->crud->getModel();
        $model = $model::query();
        $this->applyFilter($model);
        $this->applySearchFilters($model);
        $this->applyOrder($model);
        $this->applyPaginate($model);

        return $model;
    }

    public function find($id)
    {
        $model = $this->crud->getModel();
        $model = $model::query();
        $this->applyFilter($model);

        $model = $model->find($id);
        if (!$model) {
            throw new \RuntimeException(sprintf('Not allowed or no record to edit [%s]', $id));
        }

        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);

        return $model->delete();
    }

    public function store(Request $request)
    {
        $fields = $this->crud->getFieldsWithoutMarkup();
        $model = $this->crud->getModel();

        $data = [];
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            if ($field->hidden('create') || /*$field->isReadonly() || */$field->shouldSkip($request)) {
                continue;
            }
            $data += [$field->name() => $field->value($request)];
        }

        $model = $model::create($data);

        /** @var AbstractField $field */
        foreach ($fields as $field) {
            $field->afterStore($model, $request);
        }
    }

    public function update($id, Request $request)
    {
        $fields = $this->crud->getFieldsWithoutMarkup();
        $model = $this->find($id);

        $data = [];
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            if ($field->hidden('edit') || $field->isReadonly() || $field->shouldSkip($request)) {
                continue;
            }
            $data += [$field->name() => $field->value($request)];
        }

        $model->update($data);

        /** @var AbstractField $field */
        foreach ($fields as $field) {
            $field->afterUpdate($model, $request);
        }
    }

    public function updateField($id, Request $request, AbstractField $field, $value)
    {
        $model = $this->find($id);

        if (!$field->isInline() || $field->isReadonly() || $field->shouldSkip($request)) {
            return;
        }

        $model->update([
            $field->name() => $value,
        ]);
        $field->afterUpdate($model, $request);

        return $model;
    }

    public function filter(\Closure $callback)
    {
        $this->filter = $callback;
    }

    public function order(string $column, string $direction)
    {
        $this->defaultOrder = [
            'column'    => $column,
            'direction' => $direction,
        ];
    }

    public function perPage(int $perPage = null)
    {
        if (is_null($perPage)) {
            return $this->perPage;
        }

        $this->perPage = $perPage;
    }

    private function applyFilter($model)
    {
        $callback = $this->filter;
        if ($callback) {
            $callback($model);
        }
    }

    private function applySearchFilters($model)
    {
        foreach ($this->crud->getAllFieldObjects() as $field) {
            if ($field->filter()) {
                $field->filter()->apply($model, $field->name());
            }
        }
    }

    private function applyOrder($model)
    {
        $shouldApplyDefaultOrder = true;
        foreach ($this->crud->getAllFieldObjects() as $field) {
            if ($field->isOrderable()) {
                $direction = $this->crud->getOrderFilterParam($field->name());
                if (!is_null($direction)) {
                    $model->orderBy($field->name(), $direction);
                    $shouldApplyDefaultOrder = false;
                }
            }
        }

        if ($shouldApplyDefaultOrder && array_filter($this->defaultOrder)) {
            $model->orderBy($this->defaultOrder['column'], $this->defaultOrder['direction']);
        }
    }

    private function applyPaginate(&$model)
    {
        $model = $model->paginate($this->perPage());
    }
}
