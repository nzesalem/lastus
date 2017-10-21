<?php

namespace Nzesalem\Lastus\Traits;

use Nzesalem\Lastus\Lastus;

trait LastusTrait
{
    /**
     * Status accessor
     * @param  int $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return Lastus::statusName(static::class, $value);
    }

    /**
     * Status mutator
     * @param string $value
     * @return string
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = Lastus::statusCode(static::class, $value);
    }
    
    /**
     * Get the status code of a given status in this model
     * @param  string $statusName
     * @return int
     */
    public static function statusCode($statusName)
    {
        return Lastus::statusCode(static::class, $statusName);
    }

    /**
     * Get all the defined statuses in this model
     * @return array
     */
    public static function statuses()
    {
        return Lastus::statuses(static::class);
    }
}
