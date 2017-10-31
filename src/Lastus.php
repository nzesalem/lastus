<?php

namespace Nzesalem\Lastus;

class Lastus
{
    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;
    
    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the status name
     * @param  string    $class
     * @param  int $statusCode
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function statusName($class, $statusCode)
    {
        $classStatuses = self::getClassStatuses($class);

        $key = array_search($statusCode, $classStatuses);

        $statusName = ($key !== false) ? self::formatStatusName($key) : false;

        if (! $statusName) {
            throw new \InvalidArgumentException('The status code you provided is not valid');
        }
        return $statusName;
    }

    /**
     * Get the status code
     * @param  string    $class
     * @param  string $statusName
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    public static function getStatusCode($class, $statusName)
    {
        $classStatuses = self::getClassStatuses($class);
        
        $key = self::unformatStatusName($statusName);

        if (! array_key_exists($key, $classStatuses)) {
            throw new \InvalidArgumentException('The status name you provided is not valid');
        }
        
        return $classStatuses[$key];
    }

    /**
     * Get all defined statuses in a given model
     * @param  string    $class
     * @return array
     */
    public static function statuses($class)
    {
        $classStatuses = self::getClassStatuses($class);
        
        $statuses = [];
        foreach (array_keys($classStatuses) as $status) {
            $statuses[] = self::formatStatusName($status);
        }
        return $statuses;
    }

    /**
     * Get a class's statuses
     * @param  string    $class
     * @return const array
     *
     * @throws \InvalidArgumentException
     */
    protected static function getClassStatuses($class)
    {
        /**
         * @todo looks like some refactoring is needed here
         */
        if (empty($class::STATUSES) || ! empty($class::STATUSES) && ! is_array($class::STATUSES)) {
            throw new \InvalidArgumentException('STATUSES constant array was not found in specified class');
        }
        return $class::STATUSES;
    }

    /**
     * Check if logged in user is currently in a given status.
     *
     * @param string $status
     *
     * @return bool
     */
    public function userIsCurrently($status)
    {
        if ($user = $this->user()) {
            return $user->isCurrently($status);
        }
        return false;
    }

    /**
     * Get the currently authenticated user or null.
     *
     * @return Illuminate\Auth\UserInterface|null
     */
    public function user()
    {
        return $this->app->auth->user();
    }

    /**
     * @param  string $statusName
     * @return string
     */
    public static function formatStatusName($statusName)
    {
        return strtolower(str_replace('_', '-', $statusName));
    }

    /**
     * @param  string $statusName
     * @return string
     */
    public static function unformatStatusName($statusName)
    {
        return strtoupper(str_replace('-', '_', $statusName));
    }
}
