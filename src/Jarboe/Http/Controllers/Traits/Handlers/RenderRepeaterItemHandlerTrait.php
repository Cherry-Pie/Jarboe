<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Throwable;
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
        $this->beforeInit();
        $this->init();
        $this->bound();

        $errorMessages = new MessageBag(
            $this->flatten($request->get('errors', []))
        );
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

    /**
     * Flatten a multi-dimensional associative array with dots, without index part at the end.
     *
     * @return array
     */
    private function flatten($array, $prepend = '', bool $force = false): array
    {
        $results = [];

        if ($force) {
            $results[$prepend] = $array;
            return $results;
        }

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $isLastArray = false;
                foreach ($value as $innerValue) {
                    if (is_array($innerValue)) {
                        $isLastArray = false;
                        break;
                    }

                    $isLastArray = true;
                }

                if ($isLastArray) {
                    $results = array_merge($results, $this->flatten($value, $prepend . $key, true));
                } else {
                    $results = array_merge($results, $this->flatten($value, $prepend . $key . '.'));
                }
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
}
