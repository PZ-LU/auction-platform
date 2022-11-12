<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use App\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    // protected function redirectTo($request)
    // {
    //     error_log('s');
    //     return route('home');
    // }

    public function handle($request, Closure $next, ...$guards)
    {
        // error_log('This is some useful information.');
        // if ($this->authenticate($request, $guards) === 'authentication_error') {
        //     return response()->json([
        //         'error' => 'Unauthorized',
        //         'msg' => $this->authenticate($request, $guards)
        //     ]);
        // }
        $user = Auth::user();
        if ($user->status == User\Status::ACTIVE) {
            return $next($request);
        } else {
            return response()->json(['error' => 'account_suspended'], 401);
        }
    }

    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        return 'authentication_error';
    }
}
