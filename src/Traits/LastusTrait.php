<?php

namespace Nzesalem\Lastus\Traits;

use ErrorException;
use Nzesalem\Lastus\Lastus;

trait LastusTrait
{
    /**
     * Return the status field name set in Model or return status (by default)
     *
     * @return string
     */
    protected function getStatusFieldName() {
        try {
            return $this->statusFieldName;
        } catch(ErrorException $ex) {
            return 'status';
        }
    }

    /**
     * Set status field name
     *
     * @param string $fieldName
     * @return void
     */
    public function setStatusFieldName($fieldName) {
        $this->statusFieldName = $fieldName;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key, $defaultVal=null)
    {
        if ($key === $this->getStatusFieldName()) {
            $value = parent::getAttribute($key, 1);
            return $this->getLastusStatus($value);
        }

        return parent::getAttribute($key, $defaultVal);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if ($key === $this->getStatusFieldName()) {            
            $this->setLastusStatus($key, $value);
        } else {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * Status accessor
     *
     * @param  int $value
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getLastusStatus($value)
    {
        if (! is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf('Model %s should be stored as an integer, %s given', $this->getStatusFieldName(), $value));
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
    public function setLastusStatus($statusField, $value)
    {
        if (! is_string(($value))) {
            throw new \InvalidArgumentException(sprintf('Expecting a string for model %s', $this->getStatusFieldName()));
        }

        $this->attributes[$statusField] = Lastus::getStatusCode(static::class, $value);
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
