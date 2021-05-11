<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage as IlluminateStorage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Yaro\Jarboe\Table\Fields\Traits\Filename;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Storage;

class Image extends AbstractField
{
    use Storage;
    use Placeholder;
    use Nullable;
    use Filename;

    protected $encode = false;
    protected $crop = false;
    protected $shouldAutoOpen = null;
    protected $ratio = [
        'width'  => false,
        'height' => false,
    ];
    private $originalImageData;
    private $defaultImageDataStructure =  [
        'storage' => [
            'disk' => null,
            'is_encoded' => false,
        ],
        'crop' => [
            'width' => null,
            'height' => null,
            'x' => null,
            'y' => null,
            'rotate' => null,
            'rotate_background' => null,
        ],
        'sources' => [
            'original' => null,
            'cropped' => null,
        ],
    ];

    public function __construct()
    {
        $this->disk = config('filesystems.default');
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

    protected function storeFile($filepath, $filename, $width = null, $height = null, $x = null, $y = null, $rotate = null, $rotateBackgroundColor = null)
    {
        $image = InterventionImage::make($filepath);
        $rotateBackgroundColor = $rotateBackgroundColor ?: 'rgba(255, 255, 255, 0)';

        if ($rotate) {
            // because js plugin and php library rotating in different directions.
            $angle = $rotate * -1;
            $image->rotate($angle, $rotateBackgroundColor);
        }
        $hasCropProperties = !is_null($width) && !is_null($height) && !is_null($x) && !is_null($y);
        if ($this->isCrop() && $hasCropProperties) {
            $image->crop(round($width), round($height), round($x), round($y));
        }

        if ($this->isEncode()) {
            return (string) $image->encode('data-url');
        }

        $format = '';
        if ($this->isTransparentColor($rotateBackgroundColor)) {
            $format = 'png';
        }
        $path = trim($this->getPath() .'/'. $filename, '/');
        IlluminateStorage::disk($this->getDisk())->put(
            $path,
            (string) $image->encode($format)
        );

        return $path;
    }

    public function value(Request $request)
    {
        $images = $request->all($this->name());
        $images = $images[$this->name()];

        $data = [];
        foreach ($images as $image) {
            $imageData = $this->defaultImageDataStructure;
            $imageData['storage'] = [
                'disk' => $this->getDisk(),
                'is_encoded' => $this->isEncode(),
            ];

            $imageData['crop'] = $image['crop'];
            $imageData['sources'] = $image['sources'];
            /** @var UploadedFile|null $file */
            $file = $image['file'] ?? null;
            if ($file) {
                $imageData['sources']['original'] = $this->storeFile(
                    $file->getRealPath(),
                    $this->generateFilename(
                        $request,
                        $file,
                        $image,
                        $this->isTransparentColor((string) $image['crop']['rotate_background']),
                        true
                    )
                );
                $imageData['sources']['cropped'] = $this->storeFile(
                    $file->getRealPath(),
                    $this->generateFilename(
                        $request,
                        $file,
                        $image,
                        $this->isTransparentColor((string) $image['crop']['rotate_background']),
                        false
                    ),
                    $image['crop']['width'],
                    $image['crop']['height'],
                    $image['crop']['x'],
                    $image['crop']['y'],
                    $image['crop']['rotate'],
                    $image['crop']['rotate_background']
                );
            } elseif (!$image['sources']['original']) {
                continue;
            } elseif ($this->isCrop() && !$image['sources']['cropped']) {
                $imageData['sources']['cropped'] = $this->storeFile(
                    IlluminateStorage::disk($this->getDisk())->path($imageData['sources']['original']),
                    $this->generateFilename(
                        $request,
                        null,
                        $image,
                        $this->isTransparentColor((string) $image['crop']['rotate_background']),
                        false
                    ),
                    $image['crop']['width'],
                    $image['crop']['height'],
                    $image['crop']['x'],
                    $image['crop']['y'],
                    $image['crop']['rotate'],
                    $image['crop']['rotate_background']
                );
            }

            $data[] = $imageData;
        }

        if (!$this->isMultiple()) {
            $data = array_pop($data);
        }

        $data = $data ?: [];
        if ($this->isNullable()) {
            $data = $data ?: null;
        }

        return $data;
    }

    public function crop(bool $enabled = true)
    {
        $this->crop = $enabled;

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
        return view('jarboe::crud.fields.image.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.image.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.image.create', [
            'model' => null,
            'field' => $this,
        ]);
    }

    public function getImage($data = []): \Yaro\Jarboe\Pack\Image
    {
        return new \Yaro\Jarboe\Pack\Image($data);
    }

    private function isTransparentColor(string $rgbaColor)
    {
        if (!$rgbaColor) {
            return false;
        }

        $segmentsString = preg_replace('~rgba\(|\)~', '', $rgbaColor);
        $segments = explode(',', $segmentsString);

        $opacity = $segments[3] ?? 0;

        return $opacity < 1;
    }

    /**
     * @param Request $request
     * @param UploadedFile|null $file
     * @param array $imageData
     * @param bool $hasTransparentColor
     * @param bool $isOriginalImage
     * @return string
     */
    private function generateFilename(Request $request, UploadedFile $file = null, array $imageData, bool $hasTransparentColor, bool $isOriginalImage): string
    {
        $isRecropFromOriginal = !$file && !$isOriginalImage;
        $extension = $isRecropFromOriginal ? pathinfo($imageData['sources']['original'], PATHINFO_EXTENSION) : $file->extension();
        $filename = Str::random(40) .'.'. $extension;

        $closure = $this->filenameClosure;
        if (is_callable($closure)) {
            $filename = $closure($file, $request, $imageData, $isOriginalImage);
        }

        if ($hasTransparentColor) {
            $regexp = '~'. preg_quote(pathinfo($filename, PATHINFO_EXTENSION)) .'$~';
            $filename = preg_replace($regexp, 'png', $filename);
        }

        return (string) $filename;
    }

    public function getImagesPack($model): array
    {
        $defaultImage = $this->getImage();
        if (!$model) {
            return [$defaultImage];
        }

        $imagesData = $this->getAttribute($model);
        if (!is_array($imagesData)) {
            $imagesData = [];
        }

        if (!$this->isMultiple()) {
            $imagesData = [$imagesData];
        }


        $pack = [];
        foreach ($imagesData as $imageData) {
            $pack[] = $this->getImage($imageData);
        }

        return $pack ?: [$defaultImage];
    }

    public function autoOpen(bool $shouldAutoOpen = true)
    {
        $this->shouldAutoOpen = $shouldAutoOpen;

        return $this;
    }

    public function shouldAutoOpenModal(): bool
    {
        if (!is_null($this->shouldAutoOpen)) {
            return $this->shouldAutoOpen;
        }

        return $this->isCrop();
    }
}
