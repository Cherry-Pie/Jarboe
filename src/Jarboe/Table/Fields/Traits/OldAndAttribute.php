<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait OldAndAttribute
{
    public function old($name = null)
    {
        if (is_null($name)) {
            $name = $this->name();
        }

        return old($name);
    }

    public function hasOld($name = null)
    {
        return !is_null($this->old($name));
    }

    public function oldOrDefault($locale = null)
    {
        $name = $this->name();
        if ($locale) {
            $name .= '.'. $locale;
        }

        if ($this->hasOld($name)) {
            return $this->old($name);
        }

        return $this->getDefault();
    }

    public function oldOrAttribute($model, $locale = null)
    {
        $name = $this->name();
        if ($locale) {
            $name .= '.'. $locale;
        }

        if ($this->hasOld($name)) {
            return $this->old($name);
        }

        return $this->getAttribute($model, $locale);
    }

    public function getAttribute($model, $locale = null)
    {
        if ($locale && $this->isTranslatable()) {
            return $model->getTranslation($this->name(), $locale, false);
        }

        return $model->{$this->name()};
    }

    abstract public function getDefault();
    abstract public function name(string $name = null);
    abstract public function isTranslatable();
}
