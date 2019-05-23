<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Tags extends AbstractField
{
    private $relationMethod;
    private $relationTitleField;
    private $isOptionsHidden = false;

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.tags.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.tags.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.tags.create', [
            'field' => $this,
        ])->render();
    }

    public function shouldSkip(Request $request)
    {
        return true;
    }

    public function afterStore($model, Request $request)
    {
        $this->afterUpdate($model, $request);
    }

    public function afterUpdate($model, Request $request)
    {
        $relationQuery = $model->{$this->getRelationMethod()}()->getRelated();
        $relationClass = get_class($relationQuery);
        $relationClassObject = new $relationClass;

        $relatedList = [];
        $values = $request->get($this->name(), []);
        foreach ($values as $value) {
            $relatedList[] = $relationClass::firstOrCreate([
                $this->getRelationTitleField() => $value,
            ]);
        }

        $model->{$this->getRelationMethod()}()->sync(
            collect($relatedList)->pluck($relationClassObject->getKeyName())->toArray()
        );
    }

    public function relation(string $method, string $titleField)
    {
        $this->relationMethod = $method;
        $this->relationTitleField = $titleField;

        return $this;
    }

    public function getRelationMethod()
    {
        return $this->relationMethod;
    }

    public function getRelationTitleField()
    {
        return $this->relationTitleField;
    }

    public function getOptions()
    {
        $model = $this->model;
        $relationClassObject = $this->getRelationClassObject(new $model);

        $options = $relationClassObject->pluck(
            $this->getRelationTitleField(),
            $relationClassObject->getKeyName()
        );

        return $options;
    }

    public function getSelectedOptions($model)
    {
        $relationClassObject = $this->getRelationClassObject($model);

        return $model->{$this->getRelationMethod()}->pluck(
            $this->getRelationTitleField(),
            $relationClassObject->getKeyName()
        );
    }

    private function getRelationClassObject($model)
    {
        $relationQuery = $model->{$this->getRelationMethod()}()->getRelated();
        $relationClass = get_class($relationQuery);
        $relationClassObject = new $relationClass;

        return $relationClassObject;
    }

    public function isCurrentOption($option, $model = null)
    {
        if ($this->hasOld()) {
            return in_array($option, $this->old());
        }

        if (is_null($model)) {
            return false;
        }

        if ($this->isRelationField()) {
            $related = $model->{$this->getRelationMethod()};
            if ($related) {
                $collection = $related;
                if (!is_a($related, Collection::class)) {
                    $collection = collect([$related]);
                }

                return $collection->contains($this->getRelationTitleField(), $option);
            }
        }

        return false;
    }

    public function isRelationField()
    {
        return $this->getRelationTitleField() && $this->getRelationMethod();
    }

    public function hideOptions(bool $hide = true)
    {
        $this->isOptionsHidden = $hide;

        return $this;
    }

    public function isOptionsHidden()
    {
        return $this->isOptionsHidden;
    }
}
