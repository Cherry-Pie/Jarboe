<?php

namespace Yaro\Jarboe\ViewComponents\Breadcrumbs;

class Breadcrumbs implements BreadcrumbsInterface
{
    private $position = 0;
    private $crumbs = [];

    public function add(Crumb $crumb): BreadcrumbsInterface
    {
        array_push($this->crumbs, $crumb);

        return $this;
    }

    public function isEmptyForListPage(): bool
    {
        /** @var Crumb $crumb */
        foreach ($this->crumbs as $crumb) {
            if ($crumb->shouldBeShownOnListPage()) {
                return false;
            }
        }

        return true;
    }

    public function isEmptyForCreatePage(): bool
    {
        /** @var Crumb $crumb */
        foreach ($this->crumbs as $crumb) {
            if ($crumb->shouldBeShownOnCreatePage()) {
                return false;
            }
        }

        return true;
    }

    public function isEmptyForEditPage(): bool
    {
        /** @var Crumb $crumb */
        foreach ($this->crumbs as $crumb) {
            if ($crumb->shouldBeShownOnEditPage()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->crumbs[$this->key()];
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->crumbs[$this->key()]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
