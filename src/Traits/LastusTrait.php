<?php

namespace Nzesalem\Lastus\Traits;

use Nzesalem\Lastus\Lastus;

trait LastusTrait
{
    /**
     * Status accessor
     *
     * @param  int $value
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getStatusAttribute($value)
    {
        if (is_null($value)) {
            $statuses =  static::statuses();

            if (count($statuses) < 1) {
                throw new \InvalidArgumentException('STATUSES property is empty');
            }

            return $statuses[0];
        }

        if (! is_numeric($value)) {
            throw new \InvalidArgumentException('Model status should be stored as an integer');
        }
        return Lastus::statusName(static::class, $value);
    }

    /**
     * Status mutator
     *
     * @param string $value
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setStatusAttribute($value)
    {
        if (! is_string(($value))) {
            throw new \InvalidArgumentException('Expecting a string for model status');
        }
        $this->attributes['status'] = Lastus::getStatusCode(static::class, $value);
    }
    
    /**
     * Get the current status code of this model
     *
     * @return int
     */
    public function statusCode()
    {
        return Lastus::getStatusCode(static::class, $this->status);
    }

    /**
     * Get the status code of a given status in this model
     *
     * @param  string $statusName
     * @return int
     */
    public static function getStatusCode($statusName)
    {
        return Lastus::getStatusCode(static::class, $statusName);
    }
    
    /**
     * Get all the defined statuses in this model
     *
     * @return array
     */
    public static function statuses()
    {
        return Lastus::statuses(static::class);
    }

    /**
     * Checks if a model is currently in a given status.
     *
     * @param string $status
     *
     * @return bool
     */
    public function isCurrently($status)
    {
        return $this->status == $status;
    }
}
