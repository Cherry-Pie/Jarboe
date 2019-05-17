<?php

namespace Yaro\Jarboe\Etc\CustomFields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Tags;

class PermissionField extends Tags
{
    public function shouldSkip(Request $request)
    {
        return true;
    }

    public function afterStore($model, Request $request)
    {
        $this->afterUpdate($model, $request);
    }

    public function afterUpdate($model, Request $request)
    {
        $relationQuery = $model->{$this->getRelationMethod()}()->getRelated();
        $relationClass = get_class($relationQuery);

        $values = $request->get($this->name(), []);
        foreach ($values as $value) {
            $relationClass::findOrCreate($value);
        }

        $model->syncPermissions($values);
    }
}
