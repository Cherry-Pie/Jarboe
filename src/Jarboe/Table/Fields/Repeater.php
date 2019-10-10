<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yaro\Jarboe\Table\Fields\Adapters\RepeaterFile;
use Yaro\Jarboe\Table\Fields\Traits\Translatable;

class Repeater extends AbstractField
{
    use Translatable;

    private $fields = [];
    private $sortable = false;

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
        if (is_a($field, File::class)) {
            $field = new RepeaterFile($field);
        }
        $this->fields[] = $field;

        return $this;
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
            foreach ($repeaterItems as $item) {
                $itemData = [];
                /** @var AbstractField $field */
                foreach ($this->getFields() as $field) {
                    $itemData[$field->name()] = $field->value(
                        (new Request())->replace($item)
                    );
                }
                $data[] = $itemData;
            }
        }

        return $data;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.repeater.list', [
            'repeater' => $this,
            'model' => $model,
        ]);
    }

    public function getEditFormValue($model)
    {
        return view('jarboe::crud.fields.repeater.edit', [
            'model' => $model,
            'repeater' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.repeater.create', [
            'repeater' => $this,
        ]);
    }

    public function errors($messages)
    {
        $repeaterMessages = [];
        foreach ($messages as $name => $errors) {
            $errors = array_map(function ($message) use ($name) {
                $parts = explode('.', $name);
                $trueName = array_pop($parts);
                return preg_replace('~' . preg_quote($name) . '~', $trueName, $message);
            }, $errors);

            $name = preg_replace('~\.~', '][', $name) . ']';
            $name = preg_replace('~\]~', '', $name, 1);
            $repeaterMessages[$name] = $errors;
        }

        return $repeaterMessages;
    }
}
