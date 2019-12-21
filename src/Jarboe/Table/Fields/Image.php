<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManagerStatic;
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

    protected function storeFile($filepath, $filename, $width = null, $height = null, $x = null, $y = null)
    {
        $image = InterventionImage::make($filepath);

        $hasCropProperties = !is_null($width) && !is_null($height) && !is_null($x) && !is_null($y);
        if ($this->isCrop() && $hasCropProperties) {
            $image->crop(round($width), round($height), round($x), round($y));
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
        $images = $request->all($this->name());
        $images = $images[$this->name()];
        if (!$this->isMultiple()) {
            $images = [$images];
        }

        $data = [];
        foreach ($images as $image) {
            $imageData['storage'] = [
                'disk' => $this->getDisk(),
                'is_encoded' => $this->isEncode(),
            ];
            $imageData['meta'] = [];
            $imageData['crop'] = $image['crop'];
            $imageData['sources'] = $image['sources'];
            $file = $image['file'];
            if ($file) {
                $filename = $file->hashName();
                $imageData['meta'] = $this->getUploadedFileMeta($file);
                $imageData['sources']['original'] = $this->storeFile($file->getRealPath(), 'original.'. $filename);
                $imageData['sources']['cropped'] = $this->storeFile(
                    $file->getRealPath(),
                    'cropped.'. $filename,
                    $image['crop']['width'],
                    $image['crop']['height'],
                    $image['crop']['x'],
                    $image['crop']['y']
                );
            } elseif ($this->isCrop() && !$image['sources']['cropped']) {
                $imageData['meta'] = $this->getUploadedFileMeta($file);
                $imageData['sources']['cropped'] = $this->storeFile(
                    IlluminateStorage::disk($this->getDisk())->path($imageData['sources']['original']),
                    preg_replace('~original\.~', 'cropped.', $filename),
                    $image['crop']['width'],
                    $image['crop']['height'],
                    $image['crop']['x'],
                    $image['crop']['y']
                );
            }

            $data[] = $imageData;
        }

        if (!$this->isMultiple()) {
            return array_pop($data);
        }

        return $data;
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

    protected function getUploadedFileMeta(UploadedFile $file)
    {
        return [
            'client_original_name' => $file->getClientOriginalName(),
            'client_mime_type' => $file->getClientMimeType(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->extension(),
            'size_bytes' => $file->getSize(),
        ];
    }

    public function getCroppedOrOriginalUrl($model)
    {
        $data = $this->getAttribute($model);
        $filepath = Arr::get($data, 'sources.cropped', Arr::get($data, 'sources.original'));
        if (!$filepath) {
            return;
        }
        $disk = IlluminateStorage::disk(Arr::get($data, 'storage.disk', $this->getDisk()));

        return Arr::get($data, 'storage.is_encoded', $this->isEncode()) ? $filepath : $disk->url($filepath);
    }

    public function getImage($model)
    {
        $data = [];
        if ($model) {
            $data = $this->getAttribute($model);
        }

        return new \Yaro\Jarboe\Pack\Image($data);
    }
}
