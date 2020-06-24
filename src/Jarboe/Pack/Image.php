<?php

namespace Yaro\Jarboe\Pack;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Image
{
    private $data;

    public function __construct($data)
    {
        $this->data = is_array($data) ? $data : [];
    }

    public function exist(): bool
    {
        return (bool) $this->originalSource();
    }

    public function hasCropped(): bool
    {
        return (bool) $this->croppedSource();
    }

    public function croppedOrOriginalSourceUrl($default = null)
    {
        $source = Arr::get($this->data, 'sources.cropped') ?: $this->originalSource();
        if (!$source) {
            return $default;
        }

        if ($this->isEncoded()) {
            return $source;
        }

        return Storage::disk($this->getDisk())->url($source);
    }

    public function originalSourceUrl($default = null)
    {
        $source = $this->originalSource();
        $sourceUrl = $source;
        if (!$this->isEncoded()) {
            $sourceUrl = Storage::disk($this->getDisk())->url($source);
        }

        return $source ? $sourceUrl : $default;
    }

    public function originalSource($default = null)
    {
        return Arr::get($this->data, 'sources.original') ?: $default;
    }

    public function croppedSourceUrl($default = null)
    {
        $source = $this->croppedSource();
        $sourceUrl = $source;
        if (!$this->isEncoded()) {
            $sourceUrl = Storage::disk($this->getDisk())->url($source);
        }

        return $source ? $sourceUrl : $default;
    }

    public function croppedSource($default = null)
    {
        return Arr::get($this->data, 'sources.cropped') ?: $default;
    }

    public function isEncoded(): bool
    {
        // booleans passed as string on repeater item render, so (bool) true becomes (string) "true"
        $isEncode = Arr::get($this->data, 'storage.is_encoded', false);
        if (is_string($isEncode)) {
            $isEncode = $isEncode === 'true' ? true : false;
        }

        return (bool) $isEncode;
    }

    public function getDisk()
    {
        return Arr::get($this->data, 'storage.disk');
    }

    public function cropWidth()
    {
        return Arr::get($this->data, 'crop.width');
    }

    public function cropHeight()
    {
        return Arr::get($this->data, 'crop.height');
    }

    public function cropX()
    {
        return Arr::get($this->data, 'crop.x');
    }

    public function cropY()
    {
        return Arr::get($this->data, 'crop.y');
    }

    public function cropRotate()
    {
        return Arr::get($this->data, 'crop.rotate');
    }

    public function cropRotateBackground()
    {
        return Arr::get($this->data, 'crop.rotate_background');
    }
}
