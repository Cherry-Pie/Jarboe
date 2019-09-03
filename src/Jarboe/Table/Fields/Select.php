<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Relations;

class Select extends AbstractField
{
    use Orderable;
    use Relations;
    use Nullable;

    const SIMPLE   = 'simple';
    const SELECT_2 = 'select2';

    const ALLOWED_TYPES = [
        self::SIMPLE,
        self::SELECT_2,
    ];

    protected $multiple = false;
    protected $type = self::SIMPLE;
    protected $ajax = false;
    protected $searchCallback;

    public function isSelect2Type()
    {
        return $this->type == self::SELECT_2;
    }

    public function isSimpleType()
    {
        return $this->type == self::SIMPLE;
    }

    public function type($type)
    {
        $type = strtolower($type);
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new \RuntimeException(sprintf('Not allowed type [%s] for SelectField', $type));
        }

        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function ajax(bool $ajax = true)
    {
        $this->ajax = $ajax;

        return $this;
    }

    public function isAjax()
    {
        return $this->ajax;
    }

    public function multiple(bool $multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

    public function isCurrentOption($option, $model = null, $relationIndex = 0)
    {
        if ($this->hasOld()) {
            if ($this->isGroupedRelation()) {
                $option = crc32($this->relations[$relationIndex]['group']) .'~~~'. $option;
            }
            if ($this->isMultiple()) {
                return in_array($option, $this->old());
            }
            return $this->old() == $option;
        }

        if (is_null($model)) {
            return (string) $option === (string) $this->getDefault();
        }

        if ($this->isMultiple() && !$this->isRelationField()) {
            return in_array($option, $model->{$this->name});
        }

        if ($this->isRelationField()) {
            $related = $model->{$this->getRelationMethod($relationIndex)};
            if ($related) {
                $relatedModelClass = get_class($model->{$this->getRelationMethod($relationIndex)}()->getRelated());
                $freshRelatedModel = new $relatedModelClass;
                $collection = $related;
                if (!is_a($related, Collection::class)) {
                    $collection = collect([$related]);
                }

                return $collection->contains($freshRelatedModel->getKeyName(), $option);
            }
            return false;
        }

        return (string) $option === (string) $model->{$this->name};
    }

    public function relationSearch(\Closure $callback)
    {
        $this->searchCallback = $callback;

        return $this;
    }

    public function value(Request $request)
    {
        $value = $request->get($this->name());

        if ($this->isMultiple() && !$this->isRelationField()) {
            $value = $value ?: [];
        }

        if (!$value && $this->isNullable()) {
            return null;
        }

        return $value;
    }

    public function shouldSkip(Request $request)
    {
        if ($this->isRelationField()) {
            return true;
        }

        return false;
    }

    public function afterStore($model, Request $request)
    {
        $this->afterUpdate($model, $request);
    }

    public function afterUpdate($model, Request $request)
    {
        if (!$this->isRelationField()) {
            return;
        }

        $this->syncRelations($model, $request->get($this->name()));
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.select.list', [
            'model' => $model,
            'field' => $this,
            'options' => $this->getOptions(),
        ]);
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.select.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.select.create', [
            'field' => $this,
        ]);
    }

    private function filterValuesForMorphToManyRelation($ids, $relationHash)
    {
        $filtered = [];
        foreach ($ids as $id) {
            if (strpos($id, $relationHash .'~~~') !== false) {
                $id = substr($id, 13);
                $filtered[] = $id;
            }
        }

        return $filtered;
    }

}