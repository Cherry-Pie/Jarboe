<?php

namespace Yaro\Jarboe\Table\Toolbar;

use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

abstract class AbstractTool implements ToolInterface
{
    private $crud;

    public function crud(): CRUD
    {
        return $this->crud;
    }

    public function setCrud(CRUD $crud)
    {
        $this->crud = $crud;
    }

    public function getUrl(): string
    {
        return $this->crud()->toolbarUrl($this->identifier());
    }
}
