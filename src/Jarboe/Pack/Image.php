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
        $source = Arr::get($this->data, 'sources.cropped', $this->originalSource());
        if (!$source) {
            return $default;
        }

        return Storage::disk($this->getDisk())->url($source);
    }

    public function originalSourceUrl($default = null)
    {
        return $this->originalSource() ? Storage::disk($this->getDisk())->url($this->originalSource()) : $default;
    }

    public function originalSource($default = null)
    {
        return Arr::get($this->data, 'sources.original', $default);
    }

    public function croppedSource($default = null)
    {
        return Arr::get($this->data, 'sources.cropped', $default);
    }

    public function isEncoded(): bool
    {
        return (bool) Arr::get($this->data, 'storage.is_encoded', false);
    }

    public function getDisk()
    {
        return Arr::get($this->data, 'storage.disk');
    }

    public function getMimeType()
    {
        return Arr::get($this->data, 'meta.mime_type');
    }

    public function cropWidth()
    {
        return Arr::get($this->data, 'crop.width', 0);
    }

    public function cropHeight()
    {
        return Arr::get($this->data, 'crop.height', 0);
    }

    public function cropX()
    {
        return Arr::get($this->data, 'crop.x', 0);
    }

    public function cropY()
    {
        return Arr::get($this->data, 'crop.y', 0);
    }
}
