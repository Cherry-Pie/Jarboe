<?php

namespace Yaro\Jarboe\Tests\Http\Controllers;

use Yaro\Jarboe\Http\Controllers\AbstractTableController;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Models\Model;

class TestAbstractTableController extends AbstractTableController
{
    public function init()
    {
        $this->setModel(Model::class);
        $this->softDeletes();
        $this->filter(function ($model) {
            $model->withTrashed();
        });

        $this->addFields([
            Text::make('title')->inline(),
            Text::make('description'),
        ]);
    }

    public function crud(): CRUD
    {
        return $this->crud;
    }

    public function bound()
    {
        parent::bound();
    }

    public function can($action): bool
    {
        return parent::can($action);
    }

    public function notify(
        string $title,
        string $content = null,
        int $timeout = 4000,
        string $color = null,
        string $icon = null,
        string $type = 'small'
    ) {
        parent::notify($title, $content, $timeout, $color, $icon, $type);
    }

    public function notifySmall(
        string $title,
        string $content = null,
        int $timeout = 4000,
        string $color = null,
        string $icon = null
    ) {
        parent::notifySmall($title, $content, $timeout, $color, $icon);
    }

    public function notifyBig(
        string $title,
        string $content = null,
        int $timeout = 4000,
        string $color = null,
        string $icon = null
    ) {
        parent::notifyBig($title, $content, $timeout, $color, $icon);
    }

    public function notifySmallSuccess(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifySmallSuccess($title, $content, $timeout);
    }

    public function notifySmallDanger(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifySmallDanger($title, $content, $timeout);
    }

    public function notifySmallWarning(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifySmallWarning($title, $content, $timeout);
    }
    
    public function notifySmallInfo(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifySmallInfo($title, $content, $timeout);
    }
    
    public function notifyBigSuccess(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifyBigSuccess($title, $content, $timeout);
    }
    
    public function notifyBigDanger(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifyBigDanger($title, $content, $timeout);
    }
    
    public function notifyBigWarning(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifyBigWarning($title, $content, $timeout);
    }
    
    public function notifyBigInfo(string $title, string $content = null, int $timeout = 4000)
    {
        parent::notifyBigInfo($title, $content, $timeout);
    }

    public function getListViewsAbove(): array
    {
        return parent::getListViewsAbove();
    }

    public function getListViewsBelow(): array
    {
        return parent::getListViewsBelow();
    }

    public function getEditViewsAbove(): array
    {
        return parent::getEditViewsAbove();
    }

    public function getEditViewsBelow(): array
    {
        return parent::getEditViewsBelow();
    }

    public function getCreateViewsAbove(): array
    {
        return parent::getCreateViewsAbove();
    }

    public function getCreateViewsBelow(): array
    {
        return parent::getCreateViewsBelow();
    }
}
