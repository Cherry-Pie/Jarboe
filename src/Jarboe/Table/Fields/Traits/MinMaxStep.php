<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait MinMaxStep
{
    /**
     * @var null|float
     */
    private $min = null;

    /**
     * @var null|float
     */
    private $max = null;

    /**
     * @var string|float
     */
    private $step = 'any';

    /**
     * Set value for `min` attribute.
     *
     * @param float $min
     * @return $this
     */
    public function min(float $min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Set value for `max` attribute.
     *
     * @param float $max
     * @return $this
     */
    public function max(float $max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Set value for `step` attribute. Default is `any`.
     *
     * @param float $step
     * @return $this
     */
    public function step(float $step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Check if `min` attribute values specified.
     *
     * @return bool
     */
    public function hasMin(): bool
    {
        return !is_null($this->min);
    }

    /**
     * Check if `max` attribute values specified.
     *
     * @return bool
     */
    public function hasMax(): bool
    {
        return !is_null($this->max);
    }

    /**
     * Retrieve `min` attribute value.
     *
     * @return null|float
     */
    public function getMin(): ?float
    {
        return $this->min;
    }

    /**
     * Retrieve `min` attribute value.
     *
     * @return null|float
     */
    public function getMax(): ?float
    {
        return $this->max;
    }

    /**
     * Retrieve `step` attribute value.
     *
     * @return float|string
     */
    public function getStep()
    {
        return $this->step;
    }
}
