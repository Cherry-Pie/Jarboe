<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Traits\Translatable;

class Repeater extends AbstractField
{
    use Translatable;

    private $fields = [];
    private $sortable = false;
    private $modelObject;
    private $headingName = '';

    public function sortable(bool $enable = true)
    {
        $this->sortable = $enable;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function fields(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    public function addField(AbstractField $field)
    {
        $adapter = $field::$repeaterAdapterClass;
        if ($adapter) {
            $field = new $adapter($field);
        }

        $this->fields[] = $field;

        return $this;
    }

    public function beforeUpdate($model)
    {
        $this->modelObject = $model;
    }

    public function value(Request $request)
    {
        $repeaterItems = Arr::get($request->all($this->name()), $this->name());
        $repeaterItems = is_array($repeaterItems) ? $repeaterItems : [];

        $data = [];
        if ($this->isTranslatable()) {
            foreach ($repeaterItems as $locale => $items) {
                $localeData = [];
                foreach ($items as $item) {
                    $itemData = [];
                    /** @var AbstractField $field */
                    foreach ($this->getFields() as $field) {
                        $itemData[$field->name()] = $field->value(
                            (new Request([], [], [], [], array_filter($item, function ($item) {
                                return is_object($item);
                            })))->replace($item)
                        );
                    }
                    $localeData[] = $itemData;
                }
                $data[$locale] = $localeData;
            }
        } else {
            $index = 0;
            foreach ($repeaterItems as $item) {
                $itemData = [];
                /** @var AbstractField $field */
                foreach ($this->getFields() as $field) {
                    $itemData[$field->name()] = $field->value(
                        (new Request())->replace($item)
                    );
                }
                $data[] = $itemData;
                $index++;
            }
        }

        return $data;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.repeater.list', [
            'crud' => $this->crud(),
            'repeater' => $this,
            'model' => $model,
        ]);
    }

    public function getEditFormView($model)
    {
        return view('jarboe::crud.fields.repeater.edit', [
            'crud' => $this->crud(),
            'repeater' => $this,
            'model' => $model,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.repeater.create', [
            'crud' => $this->crud(),
            'repeater' => $this,
        ]);
    }

    public function errors(array $messages): array
    {
        $errors = [];
        foreach ($messages as $key => $value) {
            foreach ($value as &$error) {
                $parts = explode('.', $key);
                $name = array_pop($parts);
                $error = str_replace($key, $name, $error);
            }
            array_set($errors, $key, $value);
        }

        return $errors[$this->name()] ?? [];
    }

    public function getRepeaterItemFormView(array $data)
    {
        $model = new class($data, \ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS) extends \ArrayObject {
            public function offsetGet($index)
            {
                if (!parent::offsetExists($index)) {
                    return null;
                }
                return parent::offsetGet($index);
            }
        };

        return view('jarboe::crud.fields.repeater.inc.item_edit', [
            'repeater' => $this,
            'model' => $model,
            'rowsLeft' => 12,
        ]);
    }

    public function heading(string $fieldName)
    {
        $this->headingName = $fieldName;

        return $this;
    }

    public function getHeadingName(): string
    {
        return $this->headingName;
    }

    public function prepare(CRUD $crud)
    {
        parent::prepare($crud);

        /** @var AbstractField $field */
        foreach ($this->getFields() as $field) {
            $field->prepare($crud);
        }
    }
}
