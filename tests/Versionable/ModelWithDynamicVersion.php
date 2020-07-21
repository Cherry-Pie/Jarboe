<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Illuminate\Database\Eloquent\Model;
use Yaro\Jarboe\Models\Traits\VersionableTrait;

class ModelWithDynamicVersion extends Model
{
    use VersionableTrait;

    const TABLENAME = 'some_data';

    public $table = self::TABLENAME;
    protected $versionClass = DynamicVersionModel::class;
}
