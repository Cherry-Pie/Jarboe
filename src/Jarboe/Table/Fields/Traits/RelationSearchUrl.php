<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait RelationSearchUrl
{
    protected $relationSearchUrl;

    public function setRelationSearchUrl($url)
    {
        $this->relationSearchUrl = $url;
    }

    public function getRelationSearchUrl()
    {
        return $this->relationSearchUrl;
    }
}
