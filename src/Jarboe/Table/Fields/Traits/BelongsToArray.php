<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait BelongsToArray
{
    public function belongsToArray(): bool
    {
        return (bool) preg_match('~^[^\[\]]+\[[^\[\]]+\]$~', $this->name());
    }

    public function getAncestorName(): string
    {
        preg_match('~^([^\[\]]+)\[[^\[\]]+\]$~', $this->name(), $matches);

        return $matches[1];
    }

    public function getDescendantName(): string
    {
        preg_match('~^[^\[\]]+\[([^\[\]]+)\]$~', $this->name(), $matches);

        return $matches[1];
    }

    public function getDotPatternName(): string
    {
        return sprintf('%s.%s', $this->getAncestorName(), $this->getDescendantName());
    }

    abstract public function name(string $name = null);
}
