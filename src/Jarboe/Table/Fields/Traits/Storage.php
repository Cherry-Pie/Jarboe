<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage as IlluminateStorage;

trait Storage
{
    protected $disk;
    protected $path = '';
    protected $multiple = false;

    protected function storeFile(UploadedFile $file, $filename, Request $request)
    {
        $path = $file->storeAs($this->getPath(), $filename, [
            'disk' => $this->getDisk(),
        ]);

        return $path;
    }

    public function multiple(bool $multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

    public function disk(string $disk)
    {
        $this->disk = $disk;

        return $this;
    }

    public function getDisk()
    {
        return $this->disk;
    }

    public function path(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getUrl($model)
    {
        if (is_null($model)) {
            return null;
        }

        $filepath = $model->{$this->name()};
        if (!$filepath) {
            return null;
        }

        $disk = IlluminateStorage::disk($this->getDisk());

        $paths = [];
        if (is_array($filepath)) {
            foreach ($filepath as $path) {
                $paths[] = $this->isEncode() ? $path : $disk->url($path);
            }
            return $paths;
        }

        return $this->isEncode() ? $filepath : $disk->url($filepath);
    }
}
