<?php

namespace Yaro\Jarboe\Table\CrudTraits;

use Closure;

trait RowAttributesTrait
{
    /**
     * @var Closure|null
     */
    private $rowAttributesCallback;
    private $rowAttributesCallbackData = null;

    /**
     * Set closure for setting custom attributes to `<tr>`.
     *
     * @param Closure $closure
     */
    public function setRowAttributes(Closure $closure)
    {
        $this->rowAttributesCallback = $closure;
    }

    /**
     * Get given row attribute.
     *
     * @param $model
     * @param string $attribute
     * @return string
     */
    public function getRowAttribute($model, string $attribute): string
    {
        $attributes = $this->getRowAttributesData($model);

        if (!isset($attributes[$attribute])) {
            return '';
        }
        return (string) $attributes[$attribute];
    }

    /**
     * Get all row attributes except given.
     *
     * @param $model
     * @param array $attributes
     * @return array
     */
    public function getRowAttributesExcept($model, array $attributes = []): array
    {
        $rowAttributes = $this->getRowAttributesData($model);
        foreach ($attributes as $attribute) {
            unset($rowAttributes[$attribute]);
        }

        return $rowAttributes;
    }

    /**
     * Get row attributes data from callback.
     *
     * @param $model
     * @return array
     */
    public function getRowAttributesData($model): array
    {
        if (is_null($this->rowAttributesCallbackData)) {
            $callback = $this->rowAttributesCallback;
            $attributes = $callback($model);
            if (!is_array($attributes)) {
                $attributes = [
                    'class' => $attributes,
                ];
            }
            $this->rowAttributesCallbackData = $attributes;
        }

        return $this->rowAttributesCallbackData;
    }

    /**
     * Flush row attributes data.
     */
    public function flushRowAttributesData()
    {
        $this->rowAttributesCallbackData = null;
    }
}