<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Yaro\Jarboe\Models\Version;

class DynamicVersionModel extends Version
{
    const TABLENAME = 'other_versions';

    public $table = self::TABLENAME ;
}
