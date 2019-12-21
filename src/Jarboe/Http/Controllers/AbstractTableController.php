<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Http\Controllers\Traits\AliasesTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\CreateHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\DeleteHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\EditHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\ForceDeleteHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\InlineHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\ListHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\OrderByHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\PerPageHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\RestoreHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\SearchHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\SearchRelationHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\SortableHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\StoreHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\ToolbarHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\Handlers\UpdateHandlerTrait;
use Yaro\Jarboe\Http\Controllers\Traits\NotifyTrait;
use Yaro\Jarboe\Table\Actions\CreateAction;
use Yaro\Jarboe\Table\Actions\DeleteAction;
use Yaro\Jarboe\Table\Actions\EditAction;
use Yaro\Jarboe\Table\Actions\ForceDeleteAction;
use Yaro\Jarboe\Table\Actions\RestoreAction;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Toolbar\TranslationLocalesSelectorTool;

/**
 * @method mixed list(Request $request)
 * @method mixed search(Request $request)
 * @method mixed create(Request $request)
 * @method mixed store(Request $request)
 * @method mixed edit(Request $request, $id)
 * @method mixed update(Request $request, $id)
 * @method mixed delete(Request $request, $id)
 * @method mixed restore(Request $request, $id)
 * @method mixed forceDelete(Request $request, $id)
 * @method mixed inline(Request $request)
 */
abstract class AbstractTableController
{
    use ValidatesRequests;
    use NotifyTrait;
    use AliasesTrait;
    use DeleteHandlerTrait;
    use ToolbarHandlerTrait;
    use InlineHandlerTrait;
    use ForceDeleteHandlerTrait;
    use RestoreHandlerTrait;
    use UpdateHandlerTrait;
    use StoreHandlerTrait;
    use ListHandlerTrait;
    use EditHandlerTrait;
    use CreateHandlerTrait;
    use OrderByHandlerTrait;
    use PerPageHandlerTrait;
    use SortableHandlerTrait;
    use SearchRelationHandlerTrait;
    use SearchHandlerTrait;

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

        return $this->admin()->can($permission);
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
        } catch (UnauthorizedException $e) {
            return $this->createUnauthorizedResponse($request, $e);
        } catch (\Exception $e) {
            throw $e;
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
     * @return void
     */
    abstract protected function init();
}
