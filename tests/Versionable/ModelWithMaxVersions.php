<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Illuminate\Database\Eloquent\Model;
use Yaro\Jarboe\Models\Traits\VersionableTrait;

class ModelWithMaxVersions extends Model
{
    use VersionableTrait;

    protected $table = "users";

    protected $keepOldVersions = 2;
}
