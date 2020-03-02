<?php

namespace Yaro\Jarboe\Http\Controllers\Traits;

use Closure;
use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

trait AliasesTrait
{
    /**
     * Get admin user object.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function admin()
    {
        return admin_user();
    }

    /**
     * Add locales for all translatable fields.
     *
     * @param array $locales
     */
    protected function locales(array $locales)
    {
        $this->crud()->locales($locales);
    }

    protected function addTools(array $tools)
    {
        foreach ($tools as $tool) {
            $this->addTool($tool);
        }
    }

    protected function addTool(ToolInterface $tool)
    {
        $tool->setCrud($this->crud());
        $this->crud()->addTool($tool);
    }

    /**
     * @param string|AbstractField $column
     */
    protected function addColumn($column)
    {
        $this->crud()->addColumn($column);
    }

    protected function addColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    protected function addField(AbstractField $field)
    {
        $this->crud()->addField($field);
    }

    protected function addFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    protected function addTab($title, array $fields)
    {
        foreach ($fields as $field) {
            $field->tab($title);
            $this->addField($field);
        }
    }

    protected function setModel($model)
    {
        $this->crud()->setModel($model);
    }

    protected function paginate($perPage)
    {
        $this->crud()->paginate($perPage);
    }

    protected function order(string $column, string $direction = 'asc')
    {
        $this->crud()->order($column, $direction);
    }

    protected function filter(\Closure $callback)
    {
        $this->crud()->filter($callback);
    }

    /**
     * @param $ident
     * @return AbstractAction|null
     */
    protected function action($ident)
    {
        return $this->crud()->actions()->find($ident);
    }

    protected function removeAction($ident)
    {
        $this->crud()->actions()->remove($ident);
    }

    public function enableBatchCheckboxes(bool $enabled = true)
    {
        $this->crud()->enableBatchCheckboxes($enabled);
    }

    /**
     * Enable soft deletes for table.
     *
     * @param bool $enabled
     */
    public function softDeletes(bool $enabled = true)
    {
        $this->crud()->enableSoftDelete($enabled);
    }

    /**
     * Allows to reorder table rows.
     *
     * @param string $field
     */
    public function sortable(string $field)
    {
        $this->crud()->enableSortableByWeight($field);
    }

    /**
     * Add row action button with optional changing order.
     *
     * @param AbstractAction $action
     * @param null|string $moveDirection Move action 'before' or 'after' $baseActionIdent
     * @param null|string $baseActionIdent
     */
    protected function addAction(AbstractAction $action, $moveDirection = null, $baseActionIdent = null)
    {
        $this->crud()->actions()->add($action);
        if (is_null($moveDirection) || is_null($baseActionIdent)) {
            return;
        }

        if ($moveDirection == 'after') {
            $this->crud()->actions()->moveAfter($baseActionIdent, $action->identifier());
        } else {
            $this->crud()->actions()->moveBefore($baseActionIdent, $action->identifier());
        }
    }

    protected function addActions(array $actions)
    {
        $this->crud()->actions()->add($actions);
    }

    protected function setActions(array $actions = [])
    {
        $this->crud()->actions()->set($actions);
    }

    /**
     * Set closure for setting custom attributes to `<tr>`.
     *
     * @param Closure $closure
     */
    public function setRowAttributes(Closure $closure)
    {
        $this->crud()->setRowAttributes($closure);
    }

    abstract protected function crud(): CRUD;
}
