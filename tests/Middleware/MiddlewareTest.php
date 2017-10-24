<?php

namespace Nzesalem\Lastus\Tests\Middleware;

use Mockery as Mock;
use Nzesalem\Lastus\Tests\TestCase;

abstract class MiddlewareTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        Mock::close();
    }
    protected function mockRequest()
    {
        $user = Mock::mock('_mockedUser')->makePartial();
        $request = Mock::mock('Illuminate\Http\Request')
            ->shouldReceive('user')
            ->andReturn($user)
            ->getMock();
        return $request;
    }
}
