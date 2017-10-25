<?php

namespace Nzesalem\Lastus\Middleware;

/**
 * @package Nzesalem\Lastus
 */
use Closure;
use Illuminate\Contracts\Auth\Guard;

class LastusUserStatus
{
    protected $auth;
    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $status
     * @return mixed
     */
    public function handle($request, Closure $next, $status)
    {
        if ($this->auth->guest() || !$request->user()->isCurrently($status)) {
            abort(403, 'Account is not in the right status to view this page');
        }
        
        return $next($request);
    }
}
