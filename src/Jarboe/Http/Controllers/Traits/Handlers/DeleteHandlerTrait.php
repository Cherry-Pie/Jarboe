<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait DeleteHandlerTrait
{
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

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
}
