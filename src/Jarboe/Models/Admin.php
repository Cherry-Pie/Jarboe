<?php

namespace Yaro\Jarboe\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Yaro\Jarboe\Pack\Image;

class Admin extends Authenticatable
{
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'otp_secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'avatar' => 'array',
    ];

    public function getAvatarUrlAttribute($value)
    {
        return (new Image($this->avatar))->croppedOrOriginalSourceUrl();
    }
}
