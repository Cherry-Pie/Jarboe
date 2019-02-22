<?php

namespace Yaro\Jarboe\Table\Actions;


class DeleteAction extends AbstractAction
{

    public function __construct($ident = 'delete')
    {
        return parent::__construct($ident);
    }

    public static function make($ident = 'delete')
    {
        return parent::make($ident);
    }

}