<?php

namespace Yaro\Jarboe\ViewComponents\Breadcrumbs;

class Crumb
{
    private $url;
    private $title;
    private $shouldBeShownOnListPage = true;
    private $shouldBeShownOnCreatePage = true;
    private $shouldBeShownOnEditPage = true;

    public function __construct($title = '', $url = '')
    {
        $this->url = $url;
        $this->title = $title;
    }

    public static function make($title = '', $url = '')
    {
        return new static($title, $url);
    }

    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle($model = null)
    {
        $callback = $this->title;
        if (is_callable($callback)) {
            return $callback($model);
        }
        return $this->title;
    }

    public function getUrl($model = null)
    {
        $callback = $this->url;
        if (is_callable($callback)) {
            return $callback($model);
        }
        return $this->url;
    }

    public function showOnListPage(bool $shouldBeShown = true)
    {
        $this->shouldBeShownOnListPage = $shouldBeShown;

        return $this;
    }

    public function showOnCreatePage(bool $shouldBeShown = true)
    {
        $this->shouldBeShownOnCreatePage = $shouldBeShown;

        return $this;
    }

    public function showOnEditPage(bool $shouldBeShown = true)
    {
        $this->shouldBeShownOnEditPage = $shouldBeShown;

        return $this;
    }

    public function showOnlyOnListPage()
    {
        $this->showOnListPage(true);
        $this->showOnCreatePage(false);
        $this->showOnEditPage(false);

        return $this;
    }

    public function showOnlyOnCreatePage()
    {
        $this->showOnListPage(false);
        $this->showOnCreatePage(true);
        $this->showOnEditPage(false);

        return $this;
    }

    public function showOnlyOnEditPage()
    {
        $this->showOnListPage(false);
        $this->showOnCreatePage(false);
        $this->showOnEditPage(true);

        return $this;
    }

    public function shouldBeShownOnEditPage(): bool
    {
        return $this->shouldBeShownOnEditPage;
    }

    public function shouldBeShownOnCreatePage(): bool
    {
        return $this->shouldBeShownOnCreatePage;
    }

    public function shouldBeShownOnListPage(): bool
    {
        return $this->shouldBeShownOnListPage;
    }
}
