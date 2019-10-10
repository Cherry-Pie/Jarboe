<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait SortableHandlerTrait
{
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

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
