<?php

namespace Yaro\Jarboe\Table\Fields;


use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Interfaces\FieldPropsInterface;
use Yaro\Jarboe\Table\Filters\AbstractFilter;

abstract class AbstractField implements FieldPropsInterface
{
    const DEFAULT_TAB_IDENT = '[extra]';

    protected $model = '';
    protected $title = '';
    protected $name  = '';
    protected $readonly = false;
    protected $default = null;
    protected $hidden = [
        'list'   => false,
        'edit'   => false,
        'create' => false,
    ];
    protected $width;
    protected $filter = null;
    protected $tab = self::DEFAULT_TAB_IDENT;
    protected $col = 12;
    protected $placeholder;

    public static function make($name = '', $title = '')
    {
        $field = new static;

        if (!$title) {
            $title = preg_replace('~_~', ' ', ucfirst($name));
        }
        $field->title($title);
        $field->name($name);

        return $field;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
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

    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function col(int $col)
    {
        $this->col = $col;

        return $this;
    }

    public function getCol()
    {
        return $this->col;
    }

    public function filter(AbstractFilter $filter = null)
    {
        if (is_null($filter)) {
            return $this->filter;
        }

        $filter->field($this);

        $this->filter = $filter;

        return $this;
    }

    public function tab(string $tab)
    {
        $this->tab = $tab;

        return $this;
    }

    public function getTab()
    {
        return $this->tab;
    }

    public function title(string $title = null)
    {
        if (is_null($title)) {
            return $this->title;
        }

        $this->title = $title;

        return $this;
    }

    public function name(string $name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    public function readonly(bool $isReadonly = true)
    {
        $this->readonly = $isReadonly;

        return $this;
    }

    public function isReadonly()
    {
        return $this->readonly;
    }

    public function default($value)
    {
        $this->default = $value;

        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function old($name = null)
    {
        if (is_null($name)) {
            $name = $this->name();
        }
        
        return old($name);
    }

    public function hasOld($name = null)
    {
        return !is_null($this->old($name));
    }

    public function oldOrDefault($locale = null)
    {
        $name = $this->name();
        if ($locale) {
            $name .= '.'. $locale;
        }

        if ($this->hasOld($name)) {
            return $this->old($name);
        }

        return $this->getDefault();
    }

    public function oldOrAttribute($model, $default = null, $locale = null)
    {
        $name = $this->name();
        if ($locale) {
            $name .= '.'. $locale;
        }

        if ($this->hasOld($name)) {
            return $this->old($name);
        }

        if ($this->isTranslatable()) {
            return $model->getTranslation($this->name(), $locale) ?: $default;
        }
        return $model->{$this->name()} ?: $default;
    }

    public function getAttribute($model, $locale = null)
    {
        if ($locale && $this->isTranslatable()) {
            return $model->getTranslation($this->name(), $locale);
        }

        return $model->{$this->name()};
    }

    public function width(int $width)
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function hide(bool $edit = false, bool $create = false, bool $list = false)
    {
        $this->hidden = [
            'list'   => $list,
            'edit'   => $edit,
            'create' => $create,
        ];

        return $this;
    }

    public function hideList(bool $hide = false)
    {
        $this->hidden['list'] = $hide;

        return $this;
    }

    public function hideEdit(bool $hide = false)
    {
        $this->hidden['edit'] = $hide;

        return $this;
    }

    public function hideCreate(bool $hide = false)
    {
        $this->hidden['create'] = $hide;

        return $this;
    }

    public function hidden(string $type)
    {
        if (!array_key_exists($type, $this->hidden)) {
            throw new \RuntimeException(sprintf("Wrong type [%s] for field's hidden attribute", $type));
        }

        return $this->hidden[$type];
    }

    public function afterStore($model, Request $request)
    {
        // dummy
    }

    public function afterUpdate($model, Request $request)
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

    abstract public function getListValue($model);

    abstract public function getEditFormValue($model);

    abstract public function getCreateFormValue();

    // TODO: interface type-hinting
    public function prepare(CRUD $crud)
    {
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
}
