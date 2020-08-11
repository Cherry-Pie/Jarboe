<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait OldAndAttribute
{
    public function old($name = null)
    {
        if (is_null($name)) {
            $name = $this->name();
            if ($this->belongsToArray()) {
                $name = $this->getDotPatternName();
            }
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

        if ($this->belongsToArray()) {
            $name = $this->getDotPatternName();
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

        if ($this->belongsToArray()) {
            $name = $this->getDotPatternName();
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

        if ($this->belongsToArray()) {
            $values = $model->{$this->getAncestorName()};
            return $values[$this->getDescendantName()] ?? null;
        }

        return $model->{$this->name()};
    }

    abstract public function getDefault();
    abstract public function name(string $name = null);
    abstract public function isTranslatable();
    abstract public function belongsToArray(): bool;
    abstract public function getAncestorName(): string;
    abstract public function getDescendantName(): string;
    abstract public function getDotPatternName(): string;
}
