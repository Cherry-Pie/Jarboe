<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Relations
{
    protected $perPage = 20;

    protected $options = [];
    protected $relations = [];
    protected $relationSearchUrl;
    protected $additionalCondition;

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getRelationMethod($relationIndex = 0)
    {
        return array_get($this->relations, $relationIndex .'.method');
    }

    public function getRelationTitleField($relationIndex = 0)
    {
        return array_get($this->relations, $relationIndex .'.title');
    }

    public function relation(string $method, string $titleField, string $groupTitle = '')
    {
        $this->relations[] = [
            'method' => $method,
            'title'  => $titleField,
            'group'  => $groupTitle,
        ];

        return $this;
    }

    public function isGroupedRelation()
    {
        return count($this->relations) > 1;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function getOptions(int $page = null, int $perPage = null, $query = null, &$total = 0, $relationIndex = 0)
    {
        $options = $this->options;
        if ($this->isRelationField() && !$options) {
            $options = [];
            $model = $this->model;

            $related = (new $model)->{$this->getRelationMethod($relationIndex)}()->getRelated()->query();
            if (!is_null($query)) {
                $callback = $this->searchCallback;
                if ($callback) {
                    $related = $callback($related, $this->getRelationTitleField($relationIndex), $query);
                } else {
                    $related->where($this->getRelationTitleField($relationIndex), $query);
                }
            }
            if (!is_null($page) && !is_null($perPage)) {
                $total += (clone $related)->count();

                $offset = ($page - 1) * $perPage;
                $related->limit($perPage)->offset($offset);
            }

            $callback = $this->additionalCondition;
            if ($callback) {
                $callback($related);
            }

            $relations = $related->get();
            foreach ($relations as $relation) {
                $options[$relation->getKey()] = $relation->{$this->getRelationTitleField($relationIndex)};
            }
        }

        return $options;
    }

    public function getGroupedOptions(int $page = null, int $perPage = null, $query = null, &$total = 0)
    {
        $options = [];
        foreach ($this->relations as $index => $relation) {
            $options[$relation['group']] = $this->getOptions($page, $perPage, $query, $total, $index);
        }

        return $options;
    }

    protected function getRelatedList($relationClass, $value, $ids)
    {
        $relatedList = [];
        if ($this->isMultiple()) {
            if ($ids) {
                $relatedModels = $relationClass->whereIn($relationClass->getKeyName(), $ids)->get();
                foreach ($relatedModels as $relatedModel) {
                    $relatedList[] = $relatedModel;
                }
            }
        } else {
            $relatedList[] = $relationClass->find($value);
        }
        $relatedList = array_filter($relatedList);

        return $relatedList;
    }

    public function syncRelations($model, $value)
    {
        foreach ($this->relations as $index => $relation) {
            $relationQuery = $model->{$this->getRelationMethod($index)}()->getRelated();
            $relationClass = get_class($relationQuery);
            $relationClass = new $relationClass;
            $ids = $value ?: [];
            
            switch (get_class($model->{$this->getRelationMethod($index)}())) {
                case HasMany::class:
                    $model->{$this->getRelationMethod($index)}()->update([
                        $model->{$this->getRelationMethod($index)}()->getForeignKeyName() => null,
                    ]);

                    $relatedList = $this->getRelatedList($relationClass, $value, $ids);

                    $model->{$this->getRelationMethod($index)}()->saveMany($relatedList);
                    break;
                case MorphMany::class:
                    $model->{$this->getRelationMethod($index)}()->update([
                        $model->{$this->getRelationMethod($index)}()->getMorphType() => null,
                        $model->{$this->getRelationMethod($index)}()->getForeignKeyName() => null,
                    ]);

                    $relatedList = $this->getRelatedList($relationClass, $value, $ids);
                    $model->{$this->getRelationMethod($index)}()->saveMany($relatedList);
                    break;
                case MorphToMany::class:
                    if ($relation['group']) { // is morphedByMany
                        $ids = $this->filterValuesForMorphToManyRelation($ids, crc32($relation['group']));
                        $model->{$this->getRelationMethod($index)}()->sync($ids);
                    } else { // is morphToMany
                        $model->{$this->getRelationMethod($index)}()->update([
                            $model->{$this->getRelationMethod($index)}()->getMorphType() => null,
                            $model->{$this->getRelationMethod($index)}()->getForeignPivotKeyName() => null,
                            $model->{$this->getRelationMethod($index)}()->getRelatedPivotKeyName() => null,
                        ]);

                        $relatedList = $this->getRelatedList($relationClass, $value, $ids);
                        $model->{$this->getRelationMethod($index)}()->saveMany($relatedList);
                    }
                    break;
                case BelongsTo::class:
                case MorphTo::class:
                    $relatedList = $this->getRelatedList($relationClass, $value, $ids);
                    foreach ($relatedList as $relatedModel) {
                        $model->{$this->getRelationMethod($index)}()->associate($relatedModel)->save();
                    }
                    break;
                case HasOne::class:
                    $model->{$this->getRelationMethod($index)}()->update([
                        $model->{$this->getRelationMethod($index)}()->getForeignKeyName() => null,
                    ]);

                    $relatedList = $this->getRelatedList($relationClass, $value, $ids);
                    foreach ($relatedList as $relatedModel) {
                        $model->{$this->getRelationMethod($index)}()->save($relatedModel);
                    }
                    break;
                case BelongsToMany::class:
                    $relatedList = $this->getRelatedList($relationClass, $value, $ids);
                    $model->{$this->getRelationMethod($index)}()->sync(
                        collect($relatedList)->pluck($relationClass->getKeyName())->toArray()
                    );
                    break;
            }
        }
    }

    /**
     * Get selected options array.
     * 
     * @param null $model
     * @param int $index
     * @return array
     */
    public function getSelectedOptions($model = null, $index = 0)
    {
        $options = [];
        if (is_null($model)) {
            return $options;
        }

        $relations = $model->{$this->getRelationMethod($index)};
        if (is_null($relations)) {
            return $options;
        }

        if ($this->isMultiple()) {
            foreach ($relations as $relation) {
                $options[$relation->getKey()] = $relation->{$this->getRelationTitleField($index)};
            }
        } else {
            $options[$relations->getKey()] = $relations->{$this->getRelationTitleField($index)};
        }

        return $options;
    }

    public function getSelectedGroupedOptions($model = null)
    {
        $options = [];
        foreach ($this->relations as $index => $relation) {
            $options[$relation['group']] = $this->getSelectedOptions($model, $index);
        }

        return $options;
    }

    public function isRelationField()
    {
        return (bool) $this->relations;
    }

    public function setRelationSearchUrl($url)
    {
        $this->relationSearchUrl = $url;
    }

    public function getRelationSearchUrl()
    {
        return $this->relationSearchUrl;
    }

    public function addCondition(\Closure $callback)
    {
        $this->additionalCondition = $callback;

        return $this;
    }
}
