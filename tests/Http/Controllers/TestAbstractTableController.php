<?php

namespace Yaro\Jarboe\Tests\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Http\Controllers\AbstractTableController;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Checkbox;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Models\Model;

class TestAbstractTableController extends AbstractTableController
{
    private $shouldThrowValidationException = false;
    protected $shouldEnableSoftDelete = true;
    private $beforeInitClosure;

    public function init()
    {
        $this->setModel(Model::class);
        $this->softDeletes($this->shouldEnableSoftDelete);
        $this->filter(function ($model) {
            $model->withTrashed();
        });

        $this->addFields([
            Text::make('title')->inline(),
            Text::make('description'),
            Checkbox::make('checkbox'),
        ]);
    }

    public function createUnauthorizedResponse(Request $request, UnauthorizedException $exception)
    {
        return parent::createUnauthorizedResponse($request, $exception);
    }

    public function crud(): CRUD
    {
        return parent::crud();
    }

    public function bound()
    {
        parent::bound();
    }

    public function can($action): bool
    {
        return parent::can($action);
    }

    public function setPermissions($permissions)
    {
        return $this->permissions = $permissions;
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

    public function overrideListMethodToThrowValidationException()
    {
        $this->shouldThrowValidationException = true;
    }

    public function handleList(Request $request)
    {
        if ($this->shouldThrowValidationException) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:posts|max:255',
                'body' => 'required',
            ]);
            throw new ValidationException($validator);
        }

        return parent::handleList($request);
    }

    public function disableSoftDelete()
    {
        $this->shouldEnableSoftDelete = false;
    }

    public function enableSoftDelete()
    {
        $this->shouldEnableSoftDelete = true;
    }

    public function locales(array $locales)
    {
        return parent::locales($locales);
    }

    public function addColumn($column)
    {
        return parent::addColumn($column);
    }

    public function addColumns(array $columns)
    {
        return parent::addColumns($columns);
    }

    public function addField(AbstractField $field)
    {
        return parent::addField($field);
    }

    public function addFields(array $fields)
    {
        return parent::addFields($fields);
    }

    public function paginate($perPage)
    {
        return parent::paginate($perPage);
    }

    public function enableBatchCheckboxes(bool $enabled = true)
    {
        parent::enableBatchCheckboxes($enabled);
    }

    protected function beforeInit()
    {
        parent::beforeInit();

        $closure = $this->beforeInitClosure;
        if (is_callable($closure)) {
            $closure();
        }
    }

    public function setBeforeInitClosure(\Closure $closure)
    {
        $this->beforeInitClosure = $closure;
    }
}
