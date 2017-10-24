<?php

namespace Nzesalem\Lastus\Tests\Middleware;

use Mockery as Mock;
use Nzesalem\Lastus\Middleware\LastusUserStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LastusUserStatusTest extends MiddlewareTest
{
    public function testHandleIsGuestWithMismatchStatusShouldAbort403()
    {
        $this->expectException(HttpException::class);

        $guard = Mock::mock('Illuminate\Contracts\Auth\Guard[guest]');
        $request = $this->mockRequest();
        $middleware = new LastusUserStatus($guard);

        $guard->shouldReceive('guest')->andReturn(true);
        $request->user()->shouldReceive('isCurrently')->andReturn(false);
        $middleware->handle($request, function () {
            // Noting here
        }, null, null, true);
    }
    public function testHandleIsGuestWithMatchingStatusShouldAbort403()
    {
        $this->expectException(HttpException::class);

        $guard = Mock::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();
        $middleware = new LastusUserStatus($guard);

        $guard->shouldReceive('guest')->andReturn(true);
        $request->user()->shouldReceive('isCurrently')->andReturn(true);
        $middleware->handle($request, function () {
            // Noting here
        }, null, null);
    }
    public function testHandleIsLoggedInWithMismatchStatusShouldAbort403()
    {
        $this->expectException(HttpException::class);

        $guard = Mock::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();
        $middleware = new LastusUserStatus($guard);

        $guard->shouldReceive('guest')->andReturn(false);
        $request->user()->shouldReceive('isCurrently')->andReturn(false);
        $middleware->handle($request, function () {
            // Nothing here
        }, null, null);
    }
}
