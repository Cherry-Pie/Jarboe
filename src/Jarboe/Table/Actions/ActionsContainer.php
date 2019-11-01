<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

class ActionsContainer
{
    private $actions = [];

    public function find($ident)
    {
        /** @var AbstractAction $action */
        foreach ($this->actions as $action) {
            if ($action->identifier() == $ident) {
                return $action;
            }
        }
        return null;
    }

    public function getRowActions(): array
    {
        $actions = [];
        /** @var AbstractAction $action */
        foreach ($this->actions as $action) {
            if ($action->identifier() != 'create') {
                $actions[] = $action;
            }
        }

        return $actions;
    }

    public function remove($ident)
    {
        $actions = [];
        /** @var AbstractAction $action */
        foreach ($this->actions as $action) {
            if ($action->identifier() != $ident) {
                $actions[] = $action;
            }
        }

        $this->actions = $actions;
    }

    public function add($actions)
    {
        $actions = is_array($actions) ? $actions : [$actions];
        foreach ($actions as $action) {
            $this->addAction($action);
        }
    }

    private function addAction(AbstractAction $action)
    {
        $this->actions[] = $action;
    }

    public function set(array $actions = [])
    {
        $this->actions = [];
        $this->add($actions);
    }

    public function isAllowed($ident, $model = null): bool
    {
        $action = $this->find($ident);
        if (!$action) {
            return false;
        }

        return $action->isAllowed($model);
    }

    public function shouldRender($ident, $model = null): bool
    {
        $action = $this->find($ident);
        if (!$action) {
            return false;
        }

        return $action->shouldRender($model);
    }

    public function moveAfter($baseActionIdent, $movableActionIdent)
    {
        if (!$this->find($baseActionIdent) || !$this->find($movableActionIdent)) {
            throw new \RuntimeException(
                sprintf('No action defined for identifier [%s] or [%s]', $baseActionIdent, $movableActionIdent)
            );
        }

        $actions = [];
        /** @var AbstractAction $action */
        foreach ($this->actions as $action) {
            if ($action->identifier() == $movableActionIdent) {
                continue;
            }

            $actions[] = $action;
            if ($action->identifier() == $baseActionIdent) {
                $actions[] = $this->find($movableActionIdent);
            }
        }

        $this->actions = $actions;
    }

    public function moveBefore($baseActionIdent, $movableActionIdent)
    {
        if (!$this->find($baseActionIdent) || !$this->find($movableActionIdent)) {
            throw new \RuntimeException(
                sprintf('No action defined for identifier [%s] or [%s]', $baseActionIdent, $movableActionIdent)
            );
        }

        $actions = [];
        /** @var AbstractAction $action */
        foreach ($this->actions as $action) {
            if ($action->identifier() == $movableActionIdent) {
                continue;
            }

            if ($action->identifier() == $baseActionIdent) {
                $actions[] = $this->find($movableActionIdent);
            }
            $actions[] = $action;
        }

        $this->actions = $actions;
    }

    public function setCrud(CRUD $crud)
    {
        /** @var AbstractAction $action */
        foreach ($this->actions as $action) {
            $action->setCrud($crud);
        }
    }
}
