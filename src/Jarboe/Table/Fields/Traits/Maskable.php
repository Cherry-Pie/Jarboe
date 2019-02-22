<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Maskable
{
    protected $maskPattern;
    protected $maskPlaceholder;

    public function mask(string $pattern, string $placeholder = '∗')
    {
        $this->maskPattern = $pattern;
        $this->maskPlaceholder = $placeholder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaskPattern()
    {
        return $this->maskPattern;
    }

    /**
     * @return mixed
     */
    public function getMaskPlaceholder()
    {
        return $this->maskPlaceholder;
    }

    public function isMaskable()
    {
        return $this->maskPattern && $this->maskPlaceholder;
    }
}
