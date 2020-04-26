<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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
}
