<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Storage;

class File extends AbstractField
{
    use Storage;
    use Nullable;

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
        $files = $request->file($this->name());
        if (!$files) {
            $defaultValue = $this->isMultiple() ? [] : '';
            return $this->isNullable() ? null : $defaultValue;
        }

        $files = is_array($files) ? $files : [$files];
        $paths = [];
        foreach ($files as $file) {
            $filename = $file->hashName();
            $paths[] = $this->storeFile($file, $filename, $request);
        }

        if (!$this->isMultiple()) {
            return array_pop($paths);
        }

        return $paths;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.file.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.file.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.file.create', [
            'field' => $this,
        ])->render();
    }
}
