<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Support\ViewErrorBag;

trait Errors
{
    public function hasError(ViewErrorBag $errors, $locale = null): bool
    {
        if ($locale) {
            return $errors->has(sprintf('%s.%s', $this->name(), $locale));
        }

        if ($this->belongsToArray()) {
            return $errors->has($this->getDotPatternName());
        }

        return $errors->has($this->name());
    }

    public function getErrors(ViewErrorBag $errors, $locale = null): array
    {
        if ($locale) {
            return $errors->get(sprintf('%s.%s', $this->name(), $locale));
        }

        if ($this->belongsToArray()) {
            return $errors->get($this->getDotPatternName());
        }

        return $errors->get($this->name());
    }

    abstract public function name(string $name = null);
    abstract public function belongsToArray(): bool;
    abstract public function getDotPatternName(): string;
}
