<?php

namespace Yaro\Jarboe\ViewComponents\Breadcrumbs;

interface BreadcrumbsInterface extends \Iterator
{
    public function add(Crumb $crumb): BreadcrumbsInterface;
    public function isEmptyForListPage(): bool;
    public function isEmptyForCreatePage(): bool;
    public function isEmptyForEditPage(): bool;
    public function isEmptyForHistoryPage(): bool;
}
