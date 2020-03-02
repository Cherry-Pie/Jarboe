<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

abstract class AbstractAction implements ActionInterface
{
    protected $ident;
    protected $checkClosure;
    protected $renderClosure = false;
    private $crud;
    private $tooltip = null;
    private $tooltipPosition = ActionInterface::TOOLTIP_POSITION_TOP;

    public function __construct()
    {
        $this->checkClosure = function () {
            return true;
        };
    }

    public static function make()
    {
        return new static();
    }

    public function setCrud(CRUD $crud)
    {
        $this->crud = $crud;
    }

    public function crud(): CRUD
    {
        return $this->crud;
    }

    public function identifier()
    {
        return $this->ident ?: static::class;
    }

    public function check(\Closure $closure = null)
    {
        $this->checkClosure = $closure;

        return $this;
    }

    public function isAllowed($model = null)
    {
        $closure = $this->checkClosure;

        return is_callable($closure) ? call_user_func_array($closure, [$model]) : false;
    }

    public function renderCheck(\Closure $closure = null)
    {
        $this->renderClosure = $closure;

        return $this;
    }

    public function shouldRender($model = null)
    {
        $closure = $this->renderClosure;
        if ($closure === false) {
            return $this->isAllowed($model);
        }

        return is_callable($closure) ? call_user_func_array($closure, [$model]) : false;
    }

    public function tooltip(string $tooltip, string $position = ActionInterface::TOOLTIP_POSITION_TOP): ActionInterface
    {
        $this->tooltip = $tooltip;
        $this->tooltipPosition = $position;

        return $this;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function getTooltipPosition(): string
    {
        switch ($this->tooltipPosition) {
            case self::TOOLTIP_POSITION_TOP:
            case self::TOOLTIP_POSITION_RIGHT:
            case self::TOOLTIP_POSITION_BOTTOM:
            case self::TOOLTIP_POSITION_LEFT:
                return $this->tooltipPosition;

            default:
                return self::TOOLTIP_POSITION_TOP;
        }
    }
}
