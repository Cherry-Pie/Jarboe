<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Relations;


class Radio extends AbstractField
{
    use Orderable;
    use Relations;

    protected $columns = 1;

    public function isCurrentOption($option, $model = null, $relationIndex = 0)
    {
        if ($this->hasOld()) {
            if ($this->isGroupedRelation()) {
                $option = crc32($this->relations[$relationIndex]['group']) .'~~~'. $option;
            }
            return $this->old() == $option;
        }

        if (is_null($model)) {
            return $option == $this->getDefault();
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

        return $option == $model->{$this->name};
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

    public function columns(int $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return int
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.radio.list', [
            'model' => $model,
            'field' => $this,
            'options' => $this->getOptions(),
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.radio.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.radio.create', [
            'field' => $this,
        ])->render();
    }

}