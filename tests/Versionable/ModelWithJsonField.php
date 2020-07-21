<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Illuminate\Database\Eloquent\Model;
use Yaro\Jarboe\Models\Traits\VersionableTrait;

class ModelWithJsonField extends Model
{
    use VersionableTrait;

    const TABLENAME = 'table_with_json_field';

    public $table = self::TABLENAME;
    protected $casts = ['json_field' => 'array'];
}
