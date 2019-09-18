<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
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
     * Permission group name.
     *
     * @var string|array
     * array(
     *     'list'        => 'permission:list',
     *     'search'      => 'permission:search',
     *     'create'      => 'permission:create',
     *     'store'       => 'permission:store',
     *     'edit'        => 'permission:edit',
     *     'update'      => 'permission:update',
     *     'inline'      => 'permission:inline',
     *     'delete'      => 'permission:delete',
     *     'restore'     => 'permission:restore',
     *     'forceDelete' => 'permission:force-delete',
     * )
     */
    protected $permissions = '';

    /**
     * @var CRUD
     */
    protected $crud;

    /**
     * ID of manipulated model.
     *
     * @var mixed
     */
    protected $idEntity;

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
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws UnauthorizedException
     */
    public function handleSearch(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->can('search')) {
            throw UnauthorizedException::forPermissions(['search']);
        }

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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function orderBy($column, $direction)
    {
        $this->crud()->saveOrderFilterParam($column, $direction);

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle store action.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleStore(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->actions()->isAllowed('create')) {
            throw new PermissionDenied();
        }

        if (!$this->can('store')) {
            throw UnauthorizedException::forPermissions(['store']);
        }

        $model = $this->crud()->repo()->store($request);
        $this->idEntity = $model->getKey();

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle inline update action.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     * @throws \ReflectionException
     */
    public function handleInline(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->can('inline')) {
            throw UnauthorizedException::forPermissions(['inline']);
        }

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
            list($rules, $messages, $attributes) = $this->getValidationDataForInlineField($request, $field->name());
            if ($rules) {
                $this->validate(
                    $request,
                    [$field->name() => $rules],
                    $messages,
                    $attributes
                );
            }
        }

        // change app locale, so translatable model's column will be set properly
        if ($locale) {
            app()->setLocale($locale);
        }

        $model = $this->crud()->repo()->updateField($id, $request, $field, $value);
        $this->idEntity = $model->getKey();

        return response()->json([
            'value' => $model->{$field->name()},
        ]);
    }

    /**
     * Get validation data for inline field.
     * 
     * @param Request $request
     * @param $name
     * @return array
     * @throws \ReflectionException
     */
    protected function getValidationDataForInlineField(Request $request, $name)
    {
        $rules = [];
        $messages = [];
        $attributes = [];

        $reflection = new \ReflectionClass(get_class($this));
        $method = $reflection->getMethod('update');
        $parameters = $method->getParameters();
        $firstParam = $parameters[0] ?? null;
        $isRequestAsFirstParameter = $firstParam && $firstParam->getClass();
        if ($isRequestAsFirstParameter) {
            $formRequestClass = $firstParam->getClass()->getName();
            /** @var FormRequest $formRequest */
            $formRequest = new $formRequestClass();
            if (method_exists($formRequest, 'rules')) {
                foreach ($formRequest->rules() as $param => $paramRules) {
                    if (preg_match('~^'. preg_quote($name) .'(\.\*)?$~', $param)) {
                        return [
                            $paramRules,
                            $formRequest->messages(),
                            $formRequest->attributes(),
                        ];
                    }
                }
            }
        }

        return [$rules, $messages, $attributes];
    }

    /**
     * Handle update action.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleUpdate(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('edit', $model)) {
            throw new PermissionDenied();
        }

        if (!$this->can('update')) {
            throw UnauthorizedException::forPermissions(['update']);
        }

        $this->crud()->repo()->update($id, $request);
        $this->idEntity = $model->getKey();

        return redirect($this->crud()->listUrl());
    }

    /**
     * Handle delete action.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleDelete(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('delete', $model)) {
            throw new PermissionDenied();
        }

        if (!$this->can('delete')) {
            throw UnauthorizedException::forPermissions(['delete']);
        }

        $this->idEntity = $model->getKey();

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
     *
     * @param $perPage
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws UnauthorizedException
     */
    public function handleList(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->can('list')) {
            throw UnauthorizedException::forPermissions(['list']);
        }

        return view($this->viewCrudList, [
            'crud' => $this->crud,
            'items' => $this->crud()->repo()->get(),
            'viewsAbove' => $this->getListViewsAbove(),
            'viewsBelow' => $this->getListViewsBelow(),
        ]);
    }

    /**
     * Get array of view's objects, that should be rendered above content of `list` view.
     *
     * @return array
     */
    protected function getListViewsAbove(): array
    {
        return [];
    }

    /**
     * Get array of view's objects, that should be rendered below content of `list` view.
     *
     * @return array
     */
    protected function getListViewsBelow(): array
    {
        return [];
    }

    /**
     * Show edit form page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleEdit(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('edit', $model)) {
            throw new PermissionDenied();
        }

        if (!$this->can('edit')) {
            throw UnauthorizedException::forPermissions(['edit']);
        }

        $this->idEntity = $model->getKey();

        return view($this->viewCrudEdit, [
            'crud' => $this->crud,
            'item' => $model,
            'viewsAbove' => $this->getEditViewsAbove(),
            'viewsBelow' => $this->getEditViewsBelow(),
        ]);
    }

    /**
     * Get array of view's objects, that should be rendered above content of `edit` view.
     *
     * @return array
     */
    protected function getEditViewsAbove(): array
    {
        return [];
    }

    /**
     * Get array of view's objects, that should be rendered below content of `edit` view.
     *
     * @return array
     */
    protected function getEditViewsBelow(): array
    {
        return [];
    }

    /**
     * Show create form page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleCreate(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->actions()->isAllowed('create')) {
            throw new PermissionDenied();
        }

        if (!$this->can('create')) {
            throw UnauthorizedException::forPermissions(['create']);
        }

        return view($this->viewCrudCreate, [
            'crud' => $this->crud,
            'viewsAbove' => $this->getCreateViewsAbove(),
            'viewsBelow' => $this->getCreateViewsBelow(),
        ]);
    }

    /**
     * Get array of view's objects, that should be rendered above content of `create` view.
     *
     * @return array
     */
    protected function getCreateViewsAbove(): array
    {
        return [];
    }

    /**
     * Get array of view's objects, that should be rendered below content of `create` view.
     *
     * @return array
     */
    protected function getCreateViewsBelow(): array
    {
        return [];
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
     * @throws UnauthorizedException
     */
    public function handleRestore(Request $request, $id)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->isSoftDeleteEnabled()) {
            throw new PermissionDenied();
        }

        if (!$this->can('restore')) {
            throw UnauthorizedException::forPermissions(['restore']);
        }

        $model = $this->crud()->repo()->find($id);
        if ($this->crud()->repo()->restore($id) || !$model->trashed()) {
            return response()->json([
                'message' => __('jarboe::common.list.restore_success_message', ['id' => $id]),
            ]);
        }

        $this->idEntity = $model->getKey();

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
     * @throws UnauthorizedException
     */
    public function handleForceDelete(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->isSoftDeleteEnabled() || !$model->trashed()) {
            throw new PermissionDenied();
        }

        if (!$this->can('force-delete')) {
            throw UnauthorizedException::forPermissions(['force-delete']);
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
     * Check if user has permission for the action.
     *
     * @param $action
     * @return bool
     */
    protected function can($action): bool
    {
        if (!$this->permissions) {
            return true;
        }
        if (is_array($this->permissions) && !array_key_exists($action, $this->permissions)) {
            return true;
        }

        if (is_array($this->permissions)) {
            $permission = $this->permissions[$action];
        } else {
            $permission = sprintf('%s:%s', $this->permissions, $action);
        }

        return admin_user()->can($permission);
    }

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

    public function __call($name, $arguments)
    {
        /** @var Request $request */
        $request = RequestFacade::instance();

        $id = null;
        if (isset($arguments[0])) {
            $id = $arguments[1] ?? $arguments[0];
        }

        try {
            switch ($name) {
                case 'list':
                    return $this->handleList($request);
                case 'search':
                    return $this->handleSearch($request);
                case 'create':
                    return $this->handleCreate($request);
                case 'store':
                    return $this->handleStore($request);
                case 'edit':
                    return $this->handleEdit($request, $id);
                case 'update':
                    return $this->handleUpdate($request, $id);
                case 'delete':
                    return $this->handleDelete($request, $id);
                case 'restore':
                    return $this->handleRestore($request, $id);
                case 'forceDelete':
                    return $this->handleForceDelete($request, $id);
                case 'inline':
                    return $this->handleInline($request);

                default:
                    throw new \RuntimeException('Invalid method ' . $name);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch(UnauthorizedException $e) {
            return $this->createUnauthorizedResponse($request, $e);
        } catch (\Exception $e) {
            $this->notifyBigDanger(get_class($e), $e->getMessage(), 0);
            return redirect()->back()->withInput($request->input());
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

    /**
     * Create response for unauthorized request.
     *
     * @param Request $request
     * @param UnauthorizedException $exception
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    protected function createUnauthorizedResponse(Request $request, UnauthorizedException $exception)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 401);
        }

        return view('jarboe::errors.401');
    }

    /**
     * Get model for current request.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function model()
    {
        if (!$this->idEntity) {
            throw new \RuntimeException('Trying to access to non-existed entity');
        }

        return $this->crud()->repo()->find($this->idEntity);
    }

    /**
     * Add notification.
     *
     * @param string $title
     * @param string|null $content
     * @param int $timeout
     * @param string|null $color
     * @param string|null $icon
     * @param string $type
     */
    protected function notify(string $title, string $content = null, int $timeout = 4000, string $color = null, string $icon = null, string $type = 'small')
    {
        $ident = 'jarboe_notifications.'. $type;

        $messages = session()->get($ident, []);
        $messages[] = [
            'title' => $title,
            'content' => $content,
            'color' => $color,
            'icon' => $icon,
            'timeout' => $timeout,
        ];

        session()->flash($ident, $messages);
    }

    protected function notifySmall(string $title, string $content = null, int $timeout = 4000, string $color = null, string $icon = null)
    {
        $this->notify($title, $content, $timeout, $color, $icon, 'small');
    }

    protected function notifyBig(string $title, string $content = null, int $timeout = 4000, string $color = null, string $icon = null)
    {
        $this->notify($title, $content, $timeout, $color, $icon, 'big');
    }

    protected function notifySmallSuccess(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#739E73', 'fa fa-check', 'small');
    }

    protected function notifySmallDanger(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C46A69', 'fa fa-warning shake animated', 'small');
    }

    protected function notifySmallWarning(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C79121', 'fa fa-shield fadeInLeft animated', 'small');
    }

    protected function notifySmallInfo(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#3276B1', 'fa fa-bell swing animated', 'small');
    }

    protected function notifyBigSuccess(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#739E73', 'fa fa-check', 'big');
    }

    protected function notifyBigDanger(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C46A69', 'fa fa-warning shake animated', 'big');
    }

    protected function notifyBigWarning(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C79121', 'fa fa-shield fadeInLeft animated', 'big');
    }

    protected function notifyBigInfo(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#3276B1', 'fa fa-bell swing animated', 'big');
    }

    /*
    |--------------------------------------------------------------------------
    | Abstract
    |--------------------------------------------------------------------------
    */

    abstract protected function init();
}
