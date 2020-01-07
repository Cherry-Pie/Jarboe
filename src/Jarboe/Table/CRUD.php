<?php

namespace Yaro\Jarboe\Table;

use Yaro\Jarboe\Table\Actions\ActionsContainer;
use Yaro\Jarboe\Table\CrudTraits\BatchCheckboxesTrait;
use Yaro\Jarboe\Table\CrudTraits\ColumnsAndFieldsTrait;
use Yaro\Jarboe\Table\CrudTraits\FormClassTrait;
use Yaro\Jarboe\Table\CrudTraits\LocalesTrait;
use Yaro\Jarboe\Table\CrudTraits\PaginateTrait;
use Yaro\Jarboe\Table\CrudTraits\PreferencesHelperTrait;
use Yaro\Jarboe\Table\CrudTraits\SoftDeleteTrait;
use Yaro\Jarboe\Table\CrudTraits\SortableWeightTrait;
use Yaro\Jarboe\Table\CrudTraits\TabsTrait;
use Yaro\Jarboe\Table\CrudTraits\ToolbarTrait;
use Yaro\Jarboe\Table\CrudTraits\UrlTrait;
use Yaro\Jarboe\Table\Repositories\ModelRepositoryInterface;
use Yaro\Jarboe\Table\Repositories\PreferencesRepository;

class CRUD
{
    use BatchCheckboxesTrait;
    use ColumnsAndFieldsTrait;
    use FormClassTrait;
    use LocalesTrait;
    use PaginateTrait;
    use PreferencesHelperTrait;
    use SoftDeleteTrait;
    use SortableWeightTrait;
    use TabsTrait;
    use ToolbarTrait;
    use UrlTrait;

    const BASE_URL_DELIMITER = '/~/';

    private $model = '';
    private $repo;
    private $preferences;
    private $actions;

    public function __construct(ModelRepositoryInterface $repo, PreferencesRepository $preferences, ActionsContainer $actions)
    {
        $this->repo = $repo;
        $this->preferences = $preferences;
        $this->actions = $actions;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function repo(): ModelRepositoryInterface
    {
        $this->repo->setCrud($this);

        return $this->repo;
    }

    public function preferences()
    {
        return $this->preferences;
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

    public function actions()
    {
        return $this->actions;
    }
}
