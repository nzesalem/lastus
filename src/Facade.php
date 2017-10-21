<?php

namespace Nzesalem\Lastus;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return Lastus::class;
    }
}
