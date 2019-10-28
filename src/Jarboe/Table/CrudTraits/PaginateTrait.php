<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait PaginateTrait
{
    private $rawPerPage;

    public function getRawPerPage()
    {
        return $this->rawPerPage;
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

    abstract public function getPerPageParam();
    abstract public function setPerPageParam($perPage);
    abstract public function repo();
}
