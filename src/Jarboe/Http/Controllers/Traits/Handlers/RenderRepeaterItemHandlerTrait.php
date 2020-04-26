<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Repeater;

trait RenderRepeaterItemHandlerTrait
{
    /**
     * Handle system render repeater's item action.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function handleRenderRepeaterItem($request, $fieldName)
    {
        $this->init();
        $this->bound();

        $errorMessages = new MessageBag($request->get('errors', []));
        view()->share(
            'errors', $errorMessages ?: new ViewErrorBag()
        );

        /** @var Repeater $repeater */
        $repeater = $this->crud()->getFieldByName($fieldName);

        return response()->json([
            'view' => $repeater->getRepeaterItemFormView(
                $request->get('data')
            )->render(),
        ]);
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
}
