<?php

namespace Yaro\Jarboe\Table\Toolbar;

use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

abstract class AbstractTool implements ToolInterface
{
    /**
     * @var CRUD
     */
    private $crud;

    /**
     * Get CRUD object.
     *
     * @return CRUD
     */
    public function crud(): CRUD
    {
        return $this->crud;
    }

    /**
     * Set CRUD object.
     *
     * @param CRUD $crud
     */
    public function setCrud(CRUD $crud)
    {
        $this->crud = $crud;
    }

    /**
     * Get tool's url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->crud()->toolbarUrl($this->identifier());
    }
}
