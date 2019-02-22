<?php

namespace Yaro\Jarboe\Table\Fields\Interfaces;


interface FieldPropsInterface
{
    public function isEncode();
    public function isInline();
    public function isMarkupRow();
    public function isMultiple();
    public function isOrderable();
    public function isRelationField();
    public function isNullable();
    public function hasTooltip();
    public function hasClipboardButton();
    public function isTranslatable();
    public function isMaskable();
}