<?php

namespace Yaro\Jarboe\Table\Actions;


class CreateAction extends AbstractAction
{

    public function __construct($ident = 'create')
    {
        return parent::__construct($ident);
    }

    public static function make($ident = 'create')
    {
        return parent::make($ident);
    }

}