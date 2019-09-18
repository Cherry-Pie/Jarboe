<?php

namespace Yaro\Jarboe\Tests\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;

    protected $table = 'default_model';

    protected $fillable = [
        'title',
        'description',
    ];
}
