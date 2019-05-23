<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Translatable
{
    protected $currentLocale;
    protected $translatable = false;
    protected $locales = [];

    public function translatable(bool $translatable = true)
    {
        $this->translatable = $translatable;

        return $this;
    }

    public function isTranslatable()
    {
        return $this->translatable;
    }

    public function locales(array $locales)
    {
        $this->locales = $locales;

        return $this;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function setCurrentLocale($locale)
    {
        $this->currentLocale = $locale;
    }

    public function isCurrentLocale($locale)
    {
        return $this->currentLocale == $locale;
    }

    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }
}
