<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Storage;
use Illuminate\Support\Facades\Storage as IlluminateStorage;
use Intervention\Image\ImageManagerStatic as InterventionImage;

class Image extends AbstractField
{
    use Storage;
    use Placeholder;

    protected $encode = false;
    protected $crop = false;
    protected $ratio = [
        'width'  => false,
        'height' => false,
    ];

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
        \Log::debug(($request->all()));
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

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.image.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.image.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.image.create', [
            'model' => null,
            'field' => $this,
        ]);
    }
}
