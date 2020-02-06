<?php

namespace Yaro\Jarboe\Table\Actions;

interface ActionInterface
{
    const TOOLTIP_POSITION_TOP = 'top';
    const TOOLTIP_POSITION_RIGHT = 'right';
    const TOOLTIP_POSITION_BOTTOM = 'bottom';
    const TOOLTIP_POSITION_LEFT = 'left';


    public function identifier();

    public function render($model = null);

    public function isAllowed($model = null);

    public function shouldRender($model = null);

    /**
     * @param string $tooltip
     * @param string $position top|right|bottom|left
     * @return ActionInterface
     */
    public function tooltip(string $tooltip, string $position = ActionInterface::TOOLTIP_POSITION_TOP): ActionInterface;

    public function getTooltip();

    public function getTooltipPosition(): string;
}
