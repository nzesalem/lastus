<?php

namespace Nzesalem\Lastus\Tests\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nzesalem\Lastus\Traits\LastusTrait;

class Email extends Authenticatable
{
    use Notifiable, LastusTrait;

    /**
     * Define statuses
     */
    const STATUSES = [
        'FAILED' => 0,
        'PENDING' => 1,
        'SENT' => 2,
        'BLOCKED' => 3,
    ];

    public $statusFieldName = 'send_status';

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
