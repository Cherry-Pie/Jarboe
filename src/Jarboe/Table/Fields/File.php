<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage as IlluminateStorage;
use Yaro\Jarboe\Table\Fields\Adapters\RepeaterFile;
use Yaro\Jarboe\Table\Fields\Traits\Filename;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Storage;

class File extends AbstractField
{
    use Storage;
    use Nullable;
    use Placeholder;
    use Filename;

    public static $repeaterAdapterClass = RepeaterFile::class;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function shouldSkip(Request $request)
    {
        $files = $request->file($this->name());

        return !$files;
    }

    public function value(Request $request)
    {
        $data = Arr::get($request->all(), $this->name());
        $paths = Arr::get($data, 'paths', []);
        $files = Arr::get($data, 'files', []);

        if (!$this->isMultiple()) {
            $files = [$files];
        }
        $files = array_filter($files);

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $filename = $this->generateFilename($request, $file);
            $paths[] = $this->storeFile($file, $filename, $request);
        }

        if ($this->isMultiple()) {
            return $paths;
        }
        return array_filter($paths) ? array_pop($paths) : null;
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.file.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.file.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.file.create', [
            'field' => $this,
        ]);
    }

    public function getPaths($model): array
    {
        if (is_null($model)) {
            return [];
        }

        $filepath = $this->getAttribute($model);
        if (!$filepath) {
            return [];
        }

        return is_array($filepath) ? $filepath : [$filepath];
    }

    public function formUrl(string $path)
    {
        $disk = IlluminateStorage::disk($this->getDisk());

        return $disk->url($path);
    }

    /**
     * @param Request $request
     * @param UploadedFile $file
     * @return string
     */
    private function generateFilename(Request $request, UploadedFile $file): string
    {
        $filename = $file->hashName();

        $closure = $this->filenameClosure;
        if (is_callable($closure)) {
            $filename = $closure($file, $request);
        }

        return (string) $filename;
    }
}
