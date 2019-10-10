<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

trait ToolbarHandlerTrait
{
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

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
}
