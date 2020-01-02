<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait RestoreHandlerTrait
{
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
        if (!$this->crud()->actions()->isAllowed('restore', $model)) {
            throw new PermissionDenied();
        }

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

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
