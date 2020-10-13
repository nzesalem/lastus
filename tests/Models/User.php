<?php

namespace Nzesalem\Lastus\Tests\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nzesalem\Lastus\Traits\LastusTrait;

class User extends Authenticatable
{
    use Notifiable, LastusTrait;

    /**
     * Define statuses
     */
    const STATUSES = [
        'UNVERIFIED' => 0,
        'ACTIVE' => 1,
        'SUSPENDED' => 2,
        'BLOCKED' => 3,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
