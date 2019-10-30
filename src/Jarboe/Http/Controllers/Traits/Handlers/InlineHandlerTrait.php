<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;

trait InlineHandlerTrait
{
    /**
     * Handle inline update action.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws PermissionDenied
     * @throws \ReflectionException
     */
    public function handleInline(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->can('inline')) {
            throw UnauthorizedException::forPermissions(['inline']);
        }

        $id = $request->get('_pk');
        $value = $request->get('_value');
        /** @var AbstractField $field */
        $field = $this->crud()->getFieldByName($request->get('_name'));
        $locale = $request->get('_locale');

        $model = $this->crud()->repo()->find($id);
        if ((!$field->isInline() && !$this->crud()->actions()->isAllowed('edit', $model)) || $field->isReadonly()) {
            throw new PermissionDenied();
        }

        if (method_exists($this, 'update')) {
            list($rules, $messages, $attributes) = $this->getValidationDataForInlineField($request, $field->name());
            if ($rules) {
                $this->validate(
                    $request,
                    [$field->name() => $rules],
                    $messages,
                    $attributes
                );
            }
        }

        // change app locale, so translatable model's column will be set properly
        if ($locale) {
            app()->setLocale($locale);
        }

        $model = $this->crud()->repo()->updateField($id, $request, $field, $value);
        $this->idEntity = $model->getKey();

        return response()->json([
            'value' => $model->{$field->name()},
        ]);
    }

    /**
     * Get validation data for inline field.
     *
     * @param Request $request
     * @param $name
     * @return array
     * @throws \ReflectionException
     */
    protected function getValidationDataForInlineField(Request $request, $name)
    {
        $rules = [];
        $messages = [];
        $attributes = [];

        $reflection = new \ReflectionClass(get_class($this));
        $method = $reflection->getMethod('update');
        $parameters = $method->getParameters();
        $firstParam = $parameters[0] ?? null;
        $isRequestAsFirstParameter = $firstParam && $firstParam->getClass();
        if ($isRequestAsFirstParameter) {
            $formRequestClass = $firstParam->getClass()->getName();
            /** @var FormRequest $formRequest */
            $formRequest = new $formRequestClass();
            if (method_exists($formRequest, 'rules')) {
                foreach ($formRequest->rules() as $param => $paramRules) {
                    if (preg_match('~^'. preg_quote($name) .'(\.\*)?$~', $param)) {
                        return [
                            $paramRules,
                            $formRequest->messages(),
                            $formRequest->attributes(),
                        ];
                    }
                }
            }
        }

        return [$rules, $messages, $attributes];
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
