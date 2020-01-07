<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait ForceDeleteHandlerTrait
{
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

        if (!$this->crud()->actions()->isAllowed('force-delete', $model)) {
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

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
