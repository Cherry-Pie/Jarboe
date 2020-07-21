<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Yaro\Jarboe\Models\Admin;
use Yaro\Jarboe\Models\Traits\VersionableTrait;

class TestVersionableUser extends Admin
{
    use VersionableTrait;

    protected $table = 'admins';
}
