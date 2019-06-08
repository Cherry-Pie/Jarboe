<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Validation\ValidationException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\Actions\CreateAction;
use Yaro\Jarboe\Table\Actions\DeleteAction;
use Yaro\Jarboe\Table\Actions\EditAction;
use Yaro\Jarboe\Table\Actions\ForceDeleteAction;
use Yaro\Jarboe\Table\Actions\RestoreAction;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Select;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;
use Yaro\Jarboe\Table\Toolbar\TranslationLocalesSelectorTool;

abstract class AbstractTableController
{
    use ValidatesRequests;

    protected $viewCrudList = 'jarboe::crud.list';
    protected $viewCrudCreate = 'jarboe::crud.create';
    protected $viewCrudEdit = 'jarboe::crud.edit';

    /**
     * @var CRUD
     */
    protected $crud;

    public function __construct()
    {
        $this->crud = app(CRUD::class);
        $this->crud()->tableIdentifier(crc32(static::class));
        $this->crud()->formClass(config('jarboe.crud.form_class'));
        $this->crud()->actions()->set([
            CreateAction::make(),
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);
    }

    protected function crud(): CRUD
    {
        return $this->crud;
    }

    /**
     * Handle search action.
     */
    public function search(Request $request)
    {
        $this->init();
        $this->bound();

        $this->crud()->saveSearchFilterParams($request->get('search', []));

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle relation search action.
     * Currently used for SelectField with type `select2` and `ajax = true`.
     *
     * @param string $field
     * @param string $page
     * @param string $term
     */
    public function searchRelation(Request $request)
    {
        $this->init();
        $this->bound();

        // TODO:
        $perPage = 10;

        $query = $request->get('term');
        $fieldName = $request->get('field');
        $page = (int) $request->get('page');

        /** @var Select $field */
        $field = $this->crud()->getFieldByName($fieldName);
        if (!$field) {
            throw new \RuntimeException(sprintf('Field [%s] not setted to crud', $fieldName));
        }

        $total = 0;
        $results = [];
        if ($field->isGroupedRelation()) {
            foreach ($field->getRelations() as $index => $group) {
                $options = $field->getOptions($page, $perPage, $query, $total, $index);
                array_walk($options, function(&$item, $key) use($group) {
                    $item = [
                        'id'   => crc32($group['group']) .'~~~'. $key,
                        'text' => $item,
                    ];
                });
                if ($options) {
                    $results[] = [
                        'text'     => $group['group'],
                        'children' => array_values($options),
                    ];
                }
            }
        } else {
            $results = $field->getOptions($page, $perPage, $query, $total);
            array_walk($results, function(&$item, $key) {
                $item = [
                    'id'   => $key,
                    'text' => $item,
                ];
            });
        }

        return response()->json([
            'results' => array_values($results),
            'pagination' => [
                'more' => $total > $page * $perPage,
            ]
        ]);
    }

    /**
     * Save direction by column.
     *
     * @param $column
     * @param $direction
     */
    public function orderBy($column, $direction)
    {
        $this->crud()->saveOrderFilterParam($column, $direction);

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle store action.
     */
    public function handleStore(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->actions()->isAllowed('create')) {
            throw new PermissionDenied();
        }

        $this->crud()->repo()->store($request);

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle inline update action.
     */
    public function inline(Request $request)
    {
        $this->init();
        $this->bound();

        $id = $request->get('_pk');
        $value = $request->get('_value');
        /** @var AbstractField $field */
        $field = $this->crud()->getFieldByName($request->get('_name'));
        $locale = $request->get('_locale');

        $model = $this->crud()->repo()->find($id);
        if ((!$field->isInline() && !$this->crud()->actions()->isAllowed('edit', $model)) || $field->isReadonly()) {
            throw new PermissionDenied();
        }

        if (method_exists($this, 'update')) {
            $reflection = new \ReflectionClass(get_class($this));
            $parameters = $reflection->getMethod('update')->getParameters();
            $firstParam = $parameters[0] ?? null;
            if ($firstParam && $firstParam->getClass()) {
                $formRequestClass = $firstParam->getClass()->getName();
                /** @var FormRequest $formRequest */
                $formRequest = new $formRequestClass();
                if (method_exists($formRequest, 'rules')) {
                    foreach ($formRequest->rules() as $param => $rules) {
                        if (preg_match('~^'. preg_quote($field->name()) .'(\.\*)?$~', $param)) {
                            $this->validate(
                                $request,
                                [$field->name() => $rules],
                                $formRequest->messages(),
                                $formRequest->attributes()
                            );
                            break;
                        }
                    }
                }
            }
        }

        // change app locale, so translatable model's column will be set properly
        if ($locale) {
            app()->setLocale($locale);
        }

        $model = $this->crud()->repo()->updateField($id, $request, $field, $value);

        return response()->json([
            'value' => $model->{$field->name()},
        ]);
    }

    /**
     * Handle update action.
     */
    public function handleUpdate(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('edit', $model)) {
            throw new PermissionDenied();
        }

        $this->crud()->repo()->update($id, $request);

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle delete action.
     */
    public function handleDelete(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('delete', $model)) {
            throw new PermissionDenied();
        }

        if ($this->crud()->repo()->delete($id)) {
            $type = 'hidden';
            try {
                $this->crud()->repo()->find($id);
            } catch (\Exception $e) {
                $type = 'removed';
            }

            return response()->json([
                'type' => $type,
                'message' => __('jarboe::common.list.delete_success_message', ['id' => $id]),
            ]);
        }

        return response()->json([
            'message' => __('jarboe::common.list.delete_failed_message', ['id' => $id]),
        ], 422);
    }

    /**
     * Handle setting per page param.
     */
    public function perPage($perPage)
    {
        $this->init();
        $this->bound();

        $this->crud()->setPerPageParam((int) $perPage);

        return redirect($this->crud()->listUrl());
    }

    /**
     * Show table list page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $this->init();
        $this->bound();

        return view($this->viewCrudList, [
            'crud' => $this->crud,
            'items' => $this->crud()->repo()->get(),
        ]);
    }

    /**
     * Show edit form page.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('edit', $model)) {
            throw new PermissionDenied();
        }

        return view($this->viewCrudEdit, [
            'crud' => $this->crud,
            'item' => $model,
        ]);
    }

    /**
     * Show create form page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->actions()->isAllowed('create')) {
            throw new PermissionDenied();
        }

        return view($this->viewCrudCreate, [
            'crud' => $this->crud,
        ]);
    }

    /**
     * Handle toolbar's tool request.
     *
     * @param Request $request
     * @param $identifier
     * @return mixed
     * @throws PermissionDenied
     */
    public function toolbar(Request $request, $identifier)
    {
        $this->init();
        $this->bound();

        /** @var ToolInterface $tool */
        $tool = $this->crud()->getTool($identifier);
        if (!$tool->check()) {
            throw new PermissionDenied();
        }

        return $tool->handle($request);
    }

    /**
     * Switch table order for making sortable table.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws PermissionDenied
     */
    public function switchSortable()
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->isSortableByWeight()) {
            throw new PermissionDenied();
        }

        $newState = !$this->crud()->isSortableByWeightActive();
        $this->crud()->setSortableOrderState($newState);

        return back();
    }

    /**
     * Change sort weight of dragged row.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     */
    public function moveItem($id, Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->isSortableByWeight()) {
            throw new PermissionDenied();
        }

        $this->crud()->repo()->reorder($id, $request->get('prev'), $request->get('next'));

        return response()->json([
            'ok' => true,
        ]);
    }

    /**
     * Restore record by its id.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     */
    public function handleRestore(Request $request, $id)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->isSoftDeleteEnabled()) {
            throw new PermissionDenied();
        }

        $model = $this->crud()->repo()->find($id);
        if ($this->crud()->repo()->restore($id) || !$model->trashed()) {
            return response()->json([
                'message' => __('jarboe::common.list.restore_success_message', ['id' => $id]),
            ]);
        }

        return response()->json([
            'message' => __('jarboe::common.list.restore_failed_message', ['id' => $id]),
        ], 422);
    }

    /**
     * Force delete record by its id.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     */
    public function handleForceDelete(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->isSoftDeleteEnabled() || !$model->trashed()) {
            throw new PermissionDenied();
        }

        if ($this->crud()->repo()->forceDelete($id)) {
            return response()->json([
                'message' => __('jarboe::common.list.force_delete_success_message', ['id' => $id]),
            ]);
        }

        return response()->json([
            'message' => __('jarboe::common.list.force_delete_failed_message', ['id' => $id]),
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    protected function addTools(array $tools)
    {
        foreach ($tools as $tool) {
            $this->addTool($tool);
        }
    }

    protected function addTool(ToolInterface $tool)
    {
        $tool->setCrud($this->crud);
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
        if (!is_null($moveDirection) && !is_null($baseActionIdent)) {
            if ($moveDirection == 'after') {
                $this->crud()->actions()->moveAfter($baseActionIdent, $action->identifier());
            } else {
                $this->crud()->actions()->moveBefore($baseActionIdent, $action->identifier());
            }
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
     * Add locales for all translatable fields.
     * @param array $locales
     */
    protected function locales(array $locales)
    {
        $this->crud()->locales($locales);
    }

    public function __call($name, $arguments)
    {
        $request = RequestFacade::instance();

        try {
            switch ($name) {
                case 'update':
                    return $this->handleUpdate($request, $arguments[0]);
                case 'store':
                    return $this->handleStore($request);
                case 'delete':
                    return $this->handleDelete($request, $arguments[0]);
                case 'restore':
                    return $this->handleRestore($request, $arguments[0]);
                case 'forceDelete':
                    return $this->handleForceDelete($request, $arguments[0]);

                default:
                    throw new \RuntimeException('Invalid method ' . $name);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Bound fields/tools/etc with global data.
     */
    protected function bound()
    {
        /** @var AbstractField $field */
        foreach ($this->crud()->getAllFieldObjects() as $field) {
            $field->prepare($this->crud);
        }

        /** @var AbstractField $field */
        foreach ($this->crud()->getFieldsWithoutMarkup() as $field) {
            if ($field->isTranslatable()) {
                $this->addTool(new TranslationLocalesSelectorTool());
                break;
            }
        }

        $this->crud()->actions()->setCrud($this->crud());
    }

    /*
    |--------------------------------------------------------------------------
    | Abstract
    |--------------------------------------------------------------------------
    */

    abstract protected function init();
}
