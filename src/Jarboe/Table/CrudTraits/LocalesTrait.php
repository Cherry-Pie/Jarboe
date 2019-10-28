<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait LocalesTrait
{
    private $locales = [];

    public function locales(array $locales)
    {
        $this->normalizeLocales($locales);
        $this->locales = $locales;

        return $this;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    private function normalizeLocales(array &$locales)
    {
        if (!is_associative_array($locales)) {
            $locales = array_combine(
                array_values($locales),
                array_values($locales)
            );
        }
    }
}
