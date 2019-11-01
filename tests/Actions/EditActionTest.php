<?php

namespace Yaro\Jarboe\Tests\Actions;

use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\Actions\EditAction;

class EditActionTest extends AbstractActionTest
{
    protected function action(): AbstractAction
    {
        return EditAction::make();
    }

    protected function identifier()
    {
        return 'edit';
    }
}
