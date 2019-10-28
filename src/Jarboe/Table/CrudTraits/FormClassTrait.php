<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait FormClassTrait
{
    private $formClass = 'col-sm-12 col-md-12 col-lg-12';

    public function formClass(string $class = null)
    {
        if (!is_null($class)) {
            $this->formClass = $class;
        }

        return $this->formClass;
    }
}
