<?php

namespace Yaro\Jarboe\Helpers;

class Locale
{
    public function current()
    {
        return session('jarboe.locale', config('jarboe.locales.default'));
    }

    public function setLocale($locale)
    {
        session(['jarboe.locale' => $locale]);
        app()->setLocale($locale);
    }

    public function all()
    {
        return config('jarboe.locales.list', []);
    }

    public function getCurrentTitle(): string
    {
        $list = $this->all();
        $title = $list[$this->current()] ?? '';

        return (string) $title;
    }
}
