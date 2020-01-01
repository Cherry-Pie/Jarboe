<?php

namespace Yaro\Jarboe\ViewComponents\Breadcrumbs;

interface BreadcrumbsInterface extends \Iterator
{
    public function add(Crumb $crumb): BreadcrumbsInterface;
}
