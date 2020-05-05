<?php

namespace Yaro\Jarboe\Table\Fields\Deprecated;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Storage;
use Illuminate\Support\Facades\Storage as IlluminateStorage;
use Intervention\Image\ImageManagerStatic as InterventionImage;

class ImageDeprecated extends AbstractField
{
    use Storage;
    use Placeholder;

    protected $encode = false;
    protected $crop = false;
    protected $ratio = [
        'width'  => false,
        'height' => false,
    ];
    protected $disk;
    protected $path = '';
    protected $multiple = false;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function shouldSkip(Request $request)
    {
        $files = $request->file($this->name());

        return !$files;
    }

    public function isEncode()
    {
        return $this->encode;
    }

    /**
     * Encode image as data-url.
     *
     * @param bool $encode
     * @return $this
     */
    public function encode(bool $encode = true)
    {
        $this->encode = $encode;

        return $this;
    }

    protected function storeFile(UploadedFile $file, $filename, Request $request, $index = 0)
    {
        if (!$this->isCrop()) {
            if ($this->isEncode()) {
                $image = InterventionImage::make($file->getRealPath());
                return (string) $image->encode('data-url');
            }
            return $file->storeAs($this->getPath(), $filename, $this->getDisk());
        }

        $image = InterventionImage::make($file->getRealPath());
        $imageCropPropsField = sprintf('__%s_%s', $this->name(), 'cropvalues');
        if ($this->isCrop() && $request->has($imageCropPropsField)) {
            $image->crop(
                round($request->input(sprintf('%s.%s.width', $imageCropPropsField, $index))),
                round($request->input(sprintf('%s.%s.height', $imageCropPropsField, $index))),
                round($request->input(sprintf('%s.%s.x', $imageCropPropsField, $index))),
                round($request->input(sprintf('%s.%s.y', $imageCropPropsField, $index)))
            );
        }

        if ($this->isEncode()) {
            return (string) $image->encode('data-url');
        }

        $path = trim($this->getPath() .'/'. $filename, '/');
        IlluminateStorage::disk($this->getDisk())->put($path, (string) $image->encode());

        return $path;
    }

    public function value(Request $request)
    {
        $files = $request->file($this->name());
        if (!$files) {
            return $this->isNullable() ? null : '';
        }

        $files = is_array($files) ? $files : [$files];
        $paths = [];
        foreach ($files as $index => $file) {
            $filename = $file->hashName();
            $paths[] = $this->storeFile($file, $filename, $request, $index);
        }

        if (!$this->isMultiple()) {
            return array_pop($paths);
        }

        return $paths;
    }

    public function crop(bool $crop = true)
    {
        $this->crop = $crop;

        return $this;
    }

    public function ratio(int $width, int $height)
    {
        $this->ratio['width'] = $width;
        $this->ratio['height'] = $height;

        return $this;
    }

    public function isCrop()
    {
        return $this->crop;
    }

    public function getRatio(string $type)
    {
        $type = strtolower($type);

        switch ($type) {
            case 'width':
            case 'height':
                return $this->ratio[$type];
            default:
                return false;
        }
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.image_deprecated.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.image_deprecated.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.image_deprecated.create', [
            'model' => null,
            'field' => $this,
        ]);
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

    public function getDisK()
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
