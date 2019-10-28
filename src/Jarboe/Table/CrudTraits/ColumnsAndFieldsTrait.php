<?php

namespace Yaro\Jarboe\Table\CrudTraits;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Text;

trait ColumnsAndFieldsTrait
{
    private $fields = [];
    private $columns = [];

    /**
     * @param string|AbstractField $column
     */
    public function addColumn($column)
    {
        $this->columns[] = $column;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get columns as field objects, or fields if there are no columns.
     *
     * @return array
     */
    public function getColumnsAsFields()
    {
        $columns = [];
        foreach ($this->getColumns() as $column) {
            if (!is_object($column)) {
                $column = $this->getFieldByName($column) ?: $this->makeDefaultColumnField($column);
            }

            if ($column) {
                $columns[] = $column;
            }
        }

        return $columns ?: $this->getFields();
    }

    /**
     * Get list of columns that initialized as field object.
     *
     * @return array
     */
    public function getColumnsWithoutRelatedField()
    {
        $columns = [];
        foreach ($this->getColumns() as $column) {
            if (is_object($column)) {
                $columns[] = $column;
            }
        }

        return $columns;
    }

    public function getAllFieldObjects()
    {
        $fieldsAndColumns = array_merge(
            $this->getFieldsWithoutMarkup(),
            $this->getColumnsWithoutRelatedField()
        );

        return $fieldsAndColumns;
    }

    /**
     * @param $name
     * @return null|AbstractField
     */
    public function getFieldByName($name)
    {
        $fields = $this->getFieldsWithoutMarkup();
        foreach ($fields as $field) {
            if ($field->name() === $name) {
                return $field;
            }
        }

        return null;
    }

    public function addField(AbstractField $field)
    {
        $this->fields[] = $field;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getFieldsWithoutMarkup(): array
    {
        $fields = [];
        foreach ($this->getFields() as $field) {
            $this->extractField($field, $fields);
        }

        return $fields;
    }

    private function extractField(AbstractField $field, &$fields)
    {
        if (!$field->isMarkupRow()) {
            $fields[] = $field;
            return;
        }

        foreach ($field->getFields() as $field) {
            $this->extractField($field, $fields);
        }
    }

    public function hasAnyFieldFilter()
    {
        /** @var AbstractField $field */
        foreach ($this->getColumnsAsFields() as $field) {
            if (!$field->hidden('list') && $field->filter()) {
                return true;
            }
        }

        return false;
    }

    private function makeDefaultColumnField($column)
    {
        return Text::make($column);
    }
}
