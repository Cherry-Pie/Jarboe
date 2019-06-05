<?php

namespace Yaro\Jarboe\Table\Repositories;

class PreferencesRepository
{
    const PREFIX = 'jarboe';

    public function saveSearchFilterParams($tableIdentifier, $params)
    {
        if (!$params) {
            return;
        }

        $key = sprintf('%s.%s.search', self::PREFIX, $tableIdentifier);

        session()->put($key, $params);
    }

    public function getOrderFilterParam($tableIdentifier, $column)
    {
        $key = sprintf('%s.%s.order.%s', self::PREFIX, $tableIdentifier, $column);

        return session($key);
    }

    public function saveOrderFilterParam($tableIdentifier, $column, $direction)
    {
        $key = sprintf('%s.%s.order', self::PREFIX, $tableIdentifier);

        session()->put($key, [
            $column => $direction,
        ]);
    }

    public function getPerPageParam($tableIdentifier)
    {
        $key = sprintf('%s.%s.per_page', self::PREFIX, $tableIdentifier);

        return session($key);
    }

    public function setPerPageParam($tableIdentifier, $perPage)
    {
        $key = sprintf('%s.%s.per_page', self::PREFIX, $tableIdentifier);

        session()->put($key, $perPage);
    }

    public function getCurrentLocale($tableIdentifier)
    {
        $key = sprintf('%s.%s.locale',self::PREFIX, $tableIdentifier);

        return session($key);
    }

    public function saveCurrentLocale($tableIdentifier, $locale)
    {
        $key = sprintf('%s.%s.locale', self::PREFIX, $tableIdentifier);

        session()->put($key, $locale);
    }

    public function isSortableByWeightActive($tableIdentifier)
    {
        $key = sprintf('%s.%s.reorder', self::PREFIX, $tableIdentifier);

        return session($key, false);
    }

    public function setSortableOrderState($tableIdentifier, bool $active)
    {
        $key = sprintf('%s.%s.reorder', self::PREFIX, $tableIdentifier);

        session()->put($key, $active);
    }

    public function resetAll()
    {
        session()->forget(self::PREFIX);
    }
}
