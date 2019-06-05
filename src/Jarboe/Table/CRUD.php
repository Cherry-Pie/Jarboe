<?php

namespace Yaro\Jarboe\Table;

use Yaro\Jarboe\Table\Actions\ActionsContainer;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Repositories\ModelRepository;
use Yaro\Jarboe\Table\Repositories\PreferencesRepository;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

class CRUD
{
    const BASE_URL_DELIMITER = '/~/';

    private $fields = [];
    private $actions;
    private $model = '';
    private $repo;
    private $preferences;
    private $tableIdentifier;
    private $rawPerPage;
    private $columns = [];
    private $formClass = 'col-sm-12 col-md-12 col-lg-12';
    private $toolbar = [];
    private $locales = [];
    private $batchCheckboxesEnabled = false;
    private $sortableWeightField = null;
    private $softDeleteEnabled = false;

    public function __construct()
    {
        $this->repo = new ModelRepository($this);
        $this->preferences = new PreferencesRepository();
        $this->actions = new ActionsContainer();
    }

    public function formClass(string $class = null)
    {
        if (!is_null($class)) {
            $this->formClass = $class;
        }

        return $this->formClass;
    }

    public function getRawPerPage()
    {
        return $this->rawPerPage;
    }

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
                $column = $this->getFieldByName($column);
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

    public function getFieldByName($name)
    {
        $fields = $this->getFieldsWithoutMarkup();
        foreach ($fields as $field) {
            if ($field->name() == $name) {
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

    public function getFieldsWithoutMarkup()
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

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function repo()
    {
        return $this->repo;
    }

    public function preferences()
    {
        return $this->preferences;
    }

    /**
     * @param int|array $perPage
     */
    public function paginate($perPage)
    {
        $this->rawPerPage = $perPage;

        $storedPerPage = $this->getPerPageParam();
        if (is_array($perPage)) {
            $perPage = array_shift($perPage);
        }
        if (!$storedPerPage) {
            $this->setPerPageParam($perPage ?: $this->repo()->perPage());
            $storedPerPage = $perPage;
        }
        $this->repo()->perPage($storedPerPage);

        return $this;
    }

    public function order(string $column, string $direction)
    {
        $this->repo()->order($column, $direction);

        return $this;
    }

    public function filter(\Closure $callback)
    {
        $this->repo()->filter($callback);

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function hasAnyFieldFilter()
    {
        foreach ($this->getColumnsAsFields() as $field) {
            if (!$field->hidden('list') && $field->filter()) {
                return true;
            }
        }

        return false;
    }

    public function editUrl($id)
    {
        return sprintf('%s%s%s', $this->baseUrl(), self::BASE_URL_DELIMITER, $id);
    }

    public function createUrl()
    {
        return sprintf('%s%screate', $this->baseUrl(), self::BASE_URL_DELIMITER);
    }

    public function deleteUrl($id)
    {
        return sprintf('%s%s%s/delete', $this->baseUrl(), self::BASE_URL_DELIMITER, $id);
    }

    public function restoreUrl($id)
    {
        return sprintf('%s%s%s/restore', $this->baseUrl(), self::BASE_URL_DELIMITER, $id);
    }

    public function forceDeleteUrl($id)
    {
        return sprintf('%s%s%s/force-delete', $this->baseUrl(), self::BASE_URL_DELIMITER, $id);
    }

    public function toolbarUrl($identifier)
    {
        return sprintf('%s%stoolbar/%s', $this->baseUrl(), self::BASE_URL_DELIMITER, $identifier);
    }

    public function listUrl()
    {
        return $this->baseUrl();
    }

    public function perPageUrl($perPage)
    {
        return sprintf('%s%sper-page/%s', $this->baseUrl(), self::BASE_URL_DELIMITER, $perPage);
    }

    public function searchUrl()
    {
        return sprintf('%s%ssearch', $this->baseUrl(), self::BASE_URL_DELIMITER);
    }

    public function relationSearchUrl()
    {
        return sprintf('%s%ssearch/relation', $this->baseUrl(), self::BASE_URL_DELIMITER);
    }

    public function inlineUrl()
    {
        return sprintf('%s%sinline', $this->baseUrl(), self::BASE_URL_DELIMITER);
    }

    public function orderUrl($column, $direction)
    {
        return sprintf('%s%sorder/%s/%s', $this->baseUrl(), self::BASE_URL_DELIMITER, $column, $direction);
    }

    public function reorderUrl()
    {
        return sprintf('%s%sreorder/switch', $this->baseUrl(), self::BASE_URL_DELIMITER);
    }

    public function reorderMoveItemUrl($id)
    {
        return sprintf('%s%sreorder/move/%s', $this->baseUrl(), self::BASE_URL_DELIMITER, $id);
    }

    public function baseUrl()
    {
        $chunks = explode(self::BASE_URL_DELIMITER, request()->url());

        return rtrim($chunks[0], '/');
    }

    public function tableIdentifier($ident = null)
    {
        if (is_null($ident)) {
            return $this->tableIdentifier;
        }

        $this->tableIdentifier = $ident;
    }

    public function saveSearchFilterParams(array $params)
    {
        $this->preferences()->saveSearchFilterParams($this->tableIdentifier(), $params);
    }

    public function getOrderFilterParam($column)
    {
        return $this->preferences()->getOrderFilterParam($this->tableIdentifier(), $column);
    }

    public function saveOrderFilterParam($column, $direction)
    {
        $this->preferences()->saveOrderFilterParam($this->tableIdentifier(), $column, $direction);
    }

    public function getPerPageParam()
    {
        $this->preferences()->getPerPageParam($this->tableIdentifier());
    }

    public function setPerPageParam($perPage)
    {
        $this->preferences()->setPerPageParam($this->tableIdentifier(), $perPage);
    }

    public function getCurrentLocale()
    {
        return $this->preferences()->getCurrentLocale($this->tableIdentifier()) ?: key($this->getLocales());
    }

    public function saveCurrentLocale($locale)
    {
        $this->preferences()->saveCurrentLocale($this->tableIdentifier(), $locale);
    }

    public function getTool($ident)
    {
        if (array_key_exists($ident, $this->toolbar)) {
            return $this->toolbar[$ident];
        }

        throw new \RuntimeException('Not allowed toolbar');
    }

    public function addTool(ToolInterface $tool)
    {
        $this->toolbar[$tool->identifier()] = $tool;
    }

    public function getTools()
    {
        return $this->toolbar;
    }

    public function getActiveHeaderToolbarTools()
    {
        $list = [];
        /** @var ToolInterface $tool */
        foreach ($this->toolbar as $tool) {
            if ($tool->position() == ToolInterface::POSITION_HEADER && $tool->check()) {
                $list[] = $tool;
            }
        }

        return $list;
    }

    public function getActiveBodyToolbarToolsOnTop()
    {
        return $this->getActiveBodyToolbarTools(ToolInterface::POSITION_BODY_TOP);
    }

    public function getActiveBodyToolbarToolsOnBottom()
    {
        return $this->getActiveBodyToolbarTools(ToolInterface::POSITION_BODY_BOTTOM);
    }

    public function getActiveBodyToolbarTools($position)
    {
        $list = [];
        /** @var ToolInterface $tool */
        foreach ($this->toolbar as $tool) {
            $isPositioned = $tool->position() == $position || $tool->position() == ToolInterface::POSITION_BODY_BOTH;
            if ($isPositioned && $tool->check()) {
                $list[] = $tool;
            }
        }

        return $list;
    }

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

    public function locales(array $locales)
    {
        $this->normalizeLocales($locales);
        $this->locales = $locales;

        return $this;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    // TODO: move helper to trait or smth
    private function isAssociativeArray(array $array)
    {
        if ($array === []) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function normalizeLocales(array &$locales)
    {
        if (!$this->isAssociativeArray($locales)) {
            $locales = array_combine(
                array_values($locales),
                array_values($locales)
            );
        }
    }

    public function actions()
    {
        return $this->actions;
    }

    public function enableBatchCheckboxes(bool $enabled = true)
    {
        $this->batchCheckboxesEnabled = $enabled;
    }

    public function isBatchCheckboxesEnabled()
    {
        return $this->batchCheckboxesEnabled;
    }

    public function enableSortableByWeight(string $field)
    {
        $this->sortableWeightField = $field;
    }

    public function isSortableByWeight()
    {
        return (bool) $this->getSortableWeightFieldName();
    }

    public function isSortableByWeightActive()
    {
        return $this->preferences()->isSortableByWeightActive($this->tableIdentifier());
    }

    public function getSortableWeightFieldName()
    {
        return $this->sortableWeightField;
    }

    public function setSortableOrderState(bool $active)
    {
        $this->preferences()->setSortableOrderState($this->tableIdentifier(), $active);
    }

    public function enableSoftDelete(bool $enabled = true)
    {
        $this->softDeleteEnabled = $enabled;
    }

    public function isSoftDeleteEnabled(): bool
    {
        return $this->softDeleteEnabled;
    }
}