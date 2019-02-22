<?php

namespace Yaro\Jarboe\Table\Filters;


class CheckboxFilter extends AbstractFilter
{
    const NO_INPUT_APPLIED = '__jarboe-no-search-input-applied';

    private $checkedTitle;
    private $uncheckedTitle;
    private $desearchTitle;

    public function __construct()
    {
        $this->checkedTitle = 'Checked';
        $this->uncheckedTitle = 'Unchecked';
        $this->desearchTitle = 'None';
    }

    public function getValue($tableIdentifier)
    {
        $key = sprintf('jarboe.%s.search.%s', $tableIdentifier, $this->field()->name());

        return session($key, self::NO_INPUT_APPLIED);
    }

    /**
     * @return string
     */
    public function getCheckedTitle(): string
    {
        return $this->checkedTitle;
    }

    /**
     * @return string
     */
    public function getDesearchTitle(): string
    {
        return $this->desearchTitle;
    }

    /**
     * @return string
     */
    public function getUncheckedTitle(): string
    {
        return $this->uncheckedTitle;
    }

    public function titles(string $checked = null, string $unchecked = null, string $desearch = null)
    {
        if (!is_null($checked)) {
            $this->checkedTitle = $checked;
        }
        if (!is_null($unchecked)) {
            $this->uncheckedTitle = $unchecked;
        }
        if (!is_null($desearch)) {
            $this->desearchTitle = $desearch;
        }

        return $this;
    }

    public function render()
    {
        return view('jarboe::crud.filters.checkbox', [
            'filter' => $this,
            'value' => $this->value(),
            'desearch' => self::NO_INPUT_APPLIED,
        ])->render();
    }

    public function apply($query)
    {
        $value = $this->value();
        if ($value == self::NO_INPUT_APPLIED) {
            return;
        }

        $query->where(
            $this->field()->name(),
            $this->sign,
            !$value && $this->field()->isNullable() ? null : $value
        );
    }
}