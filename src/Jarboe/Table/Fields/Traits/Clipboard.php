<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage as IlluminateStorage;

trait Clipboard
{
    protected $clipboard = false;
    protected $clipboardCaption = null;

    /**
     * @param bool $enabled
     * @param string|int|null|\Closure $caption
     * @return $this
     */
    public function clipboard(bool $enabled = true, $caption = null)
    {
        $this->clipboard = $enabled;
        $this->clipboardCaption = $caption;

        return $this;
    }

    public function hasClipboardButton()
    {
        return $this->clipboard;
    }

    public function getClipboardCaption($model)
    {
        $value = $this->clipboardCaption;

        return $value instanceof \Closure ? $value($model) : $value;
    }
}
