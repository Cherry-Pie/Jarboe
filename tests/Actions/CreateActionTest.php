<?php

namespace Yaro\Jarboe\Tests\Actions;

use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\Actions\CreateAction;

class CreateActionTest extends AbstractActionTest
{
    protected function action(): AbstractAction
    {
        return CreateAction::make();
    }

    protected function identifier()
    {
        return 'create';
    }
}
