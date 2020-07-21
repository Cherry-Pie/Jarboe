<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Illuminate\Database\Eloquent\Model;
use Yaro\Jarboe\Models\Traits\VersionableTrait;

class TestPartialVersionableUser extends Model
{
    use VersionableTrait;

    protected $table = "users";

    protected $dontVersionFields = ["last_login"];
}
