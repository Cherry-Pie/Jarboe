<?php

namespace Yaro\Jarboe\Tests\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yaro\Jarboe\Models\Traits\VersionableTrait;

class VersionableModel extends EloquentModel
{
    use SoftDeletes;
    use VersionableTrait;

    protected $table = 'versionable_model';

    protected $fillable = [
        'title',
        'description',
        'checkbox',
    ];
}