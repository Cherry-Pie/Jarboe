<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use RuntimeException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait RevertHandlerTrait
{
    /**
     * Revert model to specific version.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleRevert(Request $request, $id)
    {
        $this->beforeInit();
        $this->init();
        $this->bound();

        if (!$this->can('revert')) {
            throw UnauthorizedException::forPermissions(['revert']);
        }

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('history', $model)) {
            throw new PermissionDenied();
        }

        $version = $model->versions()->find($request->get('version'));
        if (is_null($version)) {
            throw new RuntimeException('Version does not exist');
        }

        $version->revert();

        return response()->json([
            'ok' => true,
            'timeline' => view('jarboe::crud.inc.history.timeline', [
                'crud' => $this->crud(),
                'item' => $model,
                'versions' => $model->versions()->latest()->paginate(8),
            ])->render(),
        ]);
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
