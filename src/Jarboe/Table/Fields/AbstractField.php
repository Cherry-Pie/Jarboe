<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Interfaces\FieldPropsInterface;
use Yaro\Jarboe\Table\Fields\Traits\Column;
use Yaro\Jarboe\Table\Fields\Traits\DefaultTrait;
use Yaro\Jarboe\Table\Fields\Traits\Filter;
use Yaro\Jarboe\Table\Fields\Traits\Hidden;
use Yaro\Jarboe\Table\Fields\Traits\Model;
use Yaro\Jarboe\Table\Fields\Traits\Name;
use Yaro\Jarboe\Table\Fields\Traits\OldAndAttribute;
use Yaro\Jarboe\Table\Fields\Traits\Readonly;
use Yaro\Jarboe\Table\Fields\Traits\Tab;
use Yaro\Jarboe\Table\Fields\Traits\Title;
use Yaro\Jarboe\Table\Fields\Traits\Width;

abstract class AbstractField implements FieldPropsInterface
{
    use Hidden;
    use Width;
    use Column;
    use Tab;
    use Readonly;
    use DefaultTrait;
    use Title;
    use Name;
    use OldAndAttribute;
    use Model;
    use Filter;

    const DEFAULT_TAB_IDENT = '[extra]';

    private $crud;
    public static $repeaterAdapterClass;

    public static function make($name = '', $title = '')
    {
        $field = new static;

        if (!$title) {
            $title = preg_replace('~_~', ' ', ucfirst($name));
        }
        $field->title($title);
        $field->name($name);
        $field->tab(self::DEFAULT_TAB_IDENT);

        return $field;
    }

    public function value(Request $request)
    {
        $value = $request->get($this->name());
        if (!$value && $this->isNullable()) {
            return null;
        }

        return $value;
    }

    public function shouldSkip(Request $request)
    {
        return false;
    }

    public function afterStore($model, Request $request)
    {
        // dummy
    }

    public function afterUpdate($model, Request $request)
    {
        // dummy
    }

    public function beforeUpdate($model)
    {
        // dummy
    }

    public function isInline()
    {
        return false;
    }

    public function isRelationField()
    {
        return false;
    }

    public function isMultiple()
    {
        return false;
    }

    public function isEncode()
    {
        return false;
    }

    public function isMarkupRow()
    {
        return false;
    }

    public function isOrderable()
    {
        return false;
    }

    public function isAjax()
    {
        return false;
    }

    public function isNullable()
    {
        return false;
    }

    public function hasTooltip()
    {
        return false;
    }

    public function hasClipboardButton()
    {
        return false;
    }

    public function isTranslatable()
    {
        return false;
    }

    public function isMaskable()
    {
        return false;
    }

    public function getPlaceholder()
    {
        return null;
    }

    public function hasMaxlength(): bool
    {
        return false;
    }

    public function getHistoryView($value)
    {
        return view('jarboe::crud.fields.abstract.history', [
            'value' => $value,
        ]);
    }

    abstract public function getListView($model);

    abstract public function getEditFormView($model);

    abstract public function getCreateFormView();

    // TODO: interface type-hinting
    public function prepare(CRUD $crud)
    {
        $this->crud = $crud;

        if ($this->filter()) {
            $this->filter()->value(
                $this->filter()->getValue($crud->tableIdentifier())
            );
        }

        $this->setModel($crud->getModel());

        if (method_exists($this, 'setRelationSearchUrl')) {
            $this->setRelationSearchUrl($crud->relationSearchUrl());
        }
        if (method_exists($this, 'setInlineUrl')) {
            $this->setInlineUrl($crud->inlineUrl());
        }
        if ($this->isTranslatable()) {
            $this->locales($crud->getLocales());
            $this->setCurrentLocale($crud->getCurrentLocale());
        }
    }

    protected function crud(): CRUD
    {
        return $this->crud;
    }
}
