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
        $this->beforeInit();
        $this->init();
        $this->bound();

        if (!$this->can('inline')) {
            throw UnauthorizedException::forPermissions(['inline']);
        }

        $id = $request->get('_pk');
        $value = $request->get('_value');
        $locale = $request->get('_locale');
        /** @var AbstractField $field */
        $field = $this->crud()->getFieldByName($request->get('_name'));

        $request->replace(
            $request->all() + [$field->name() => $value]
        );

        $model = $this->crud()->repo()->find($id);
        if (!$field->isInline() || !$this->crud()->actions()->isAllowed('edit', $model) || $field->isReadonly()) {
            throw new PermissionDenied();
        }

        if (method_exists($this, 'update')) {
            $keyName = $field->belongsToArray() ? $field->getDotPatternName() : $field->name();
            list($rules, $messages, $attributes) = $this->getValidationDataForInlineField($request, $keyName);
            if ($rules) {
                $this->validate(
                    $request,
                    [$keyName => $rules],
                    $messages,
                    $attributes
                );
            }
        }

        // change app locale, so translatable model's column will be set properly
        if ($locale) {
            app()->setLocale($locale);
        }

        $fieldName = $field->name();
        $fieldValue = $field->value($request);
        if ($field->belongsToArray()) {
            $fieldName = $field->getAncestorName();
            $arrayValue = $field->getAttribute($model);
            $arrayValue = is_array($arrayValue) ? $arrayValue : [];
            $fieldValue = $arrayValue + [$field->getDescendantName() => $fieldValue];
        }

        $model = $this->crud()->repo()->update($id, [
            $fieldName => $fieldValue,
        ]);
        $field->afterUpdate($model, $request);

        $this->idEntity = $model->getKey();

        return response()->json([
            'value' => $field->getAttribute($model, $locale),
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

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
