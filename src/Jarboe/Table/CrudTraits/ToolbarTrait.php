<?php

namespace Yaro\Jarboe\Table\CrudTraits;

use Yaro\Jarboe\Table\Toolbar\AbstractTool;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

trait ToolbarTrait
{
    private $toolbar = [];

    /**
     * @param $ident
     * @throws \RuntimeException
     * @return AbstractTool
     */
    public function getTool($ident)
    {
        if (array_key_exists($ident, $this->toolbar)) {
            return $this->toolbar[$ident];
        }

        throw new \RuntimeException('Not allowed toolbar');
    }

    public function addTool(ToolInterface $tool)
    {
        $this->toolbar[$tool->identifier()] = $tool;
    }

    public function getTools()
    {
        return $this->toolbar;
    }

    public function getActiveHeaderToolbarTools()
    {
        $list = [];
        /** @var ToolInterface $tool */
        foreach ($this->toolbar as $tool) {
            if ($tool->position() == ToolInterface::POSITION_HEADER && $tool->check()) {
                $list[] = $tool;
            }
        }

        return $list;
    }

    public function getActiveBodyToolbarToolsOnTop()
    {
        return $this->getActiveBodyToolbarTools(ToolInterface::POSITION_BODY_TOP);
    }

    public function getActiveBodyToolbarToolsOnBottom()
    {
        return $this->getActiveBodyToolbarTools(ToolInterface::POSITION_BODY_BOTTOM);
    }

    public function getActiveBodyToolbarTools($position)
    {
        $list = [];
        /** @var ToolInterface $tool */
        foreach ($this->toolbar as $tool) {
            $isPositioned = $tool->position() == $position || $tool->position() == ToolInterface::POSITION_BODY_BOTH;
            if ($isPositioned && $tool->check()) {
                $list[] = $tool;
            }
        }

        return $list;
    }
}