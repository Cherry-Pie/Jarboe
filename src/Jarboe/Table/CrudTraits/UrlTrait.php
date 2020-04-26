<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait UrlTrait
{
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

    public function renderRepeaterItemUrl($fieldName)
    {
        return sprintf('%s%srender-repeater-item/%s', $this->baseUrl(), self::BASE_URL_DELIMITER, $fieldName);
    }

    public function baseUrl()
    {
        $chunks = explode(self::BASE_URL_DELIMITER, request()->url());

        return rtrim($chunks[0], '/');
    }
}
