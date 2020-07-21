<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yaro\Jarboe\Models\Traits\VersionableTrait;
use Illuminate\Database\Eloquent\Model;

class TestVersionableSoftDeleteUser extends Model
{
    use VersionableTrait;
    use SoftDeletes;

    protected $table = "users";
}
