<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Inline
{
    protected $inline = false;
    protected $inlineOptions = [];
    protected $inlineUrl = '';

    public function inline(bool $enabled = true, array $options = [])
    {
        $this->inline = $enabled;
        $this->inlineOptions = $options;

        return $this;
    }

    public function isInline(): bool
    {
        return !$this->isReadonly() && $this->inline;
    }

    public function getInlineOptions(): array
    {
        return $this->inlineOptions;
    }

    public function setInlineUrl(string $url)
    {
        $this->inlineUrl = $url;
    }

    /**
     * @return string
     */
    public function getInlineUrl(): string
    {
        return $this->inlineUrl;
    }
}
