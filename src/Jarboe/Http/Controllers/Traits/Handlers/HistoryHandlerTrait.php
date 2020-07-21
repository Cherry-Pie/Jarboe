<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait HistoryHandlerTrait
{
    protected $historyCrudHistory = 'jarboe::crud.history';

    /**
     * Show history page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleHistory(Request $request, $id)
    {
        $this->beforeInit();
        $this->init();
        $this->bound();

        if (!$this->can('history')) {
            throw UnauthorizedException::forPermissions(['history']);
        }

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('history', $model)) {
            throw new PermissionDenied();
        }

        return view($this->historyCrudHistory, [
            'crud' => $this->crud(),
            'item' => $model,
            'versions' => $model->versions()->latest()->paginate(8),
        ]);
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
