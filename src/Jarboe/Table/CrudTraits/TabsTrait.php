<?php

namespace Yaro\Jarboe\Table\CrudTraits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait TabsTrait
{
    public function getTabErrorsCount($tabTitle, $errors)
    {
        $count = 0;
        $tabs = $this->getTabs();
        $fields = $tabs[$tabTitle];

        foreach ($fields as $field) {
            $count += count($errors->get($field->name()));
        }

        return $count;
    }

    public function getTabs($skipHiddenType = null)
    {
        $tabs = [];
        foreach ($this->getFields() as $field) {
            if ($skipHiddenType && $field->hidden($skipHiddenType)) {
                continue;
            }
            $tabs[$field->getTab()][] = $field;
        }

        $this->moveDefaultTabToEnd($tabs);

        return $tabs;
    }

    private function moveDefaultTabToEnd(&$tabs)
    {
        $defaultTab = AbstractField::DEFAULT_TAB_IDENT;
        if (!array_key_exists($defaultTab, $tabs)) {
            return;
        }

        $defaultTabFields = $tabs[$defaultTab];
        unset($tabs[$defaultTab]);
        $tabs[$defaultTab] = $defaultTabFields;
    }
}
