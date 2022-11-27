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

    protected $exception_routes = [
      'api/register',
    ];

    public function handle($request, Closure $next, ...$guards)
    {
        // TODO: delete
        // $out->writeln(URL::to('/'));
        // $out->writeln(URL::to('/'));


        foreach ($this->exception_routes as $excluded_route) {
            if ($request->path() === $excluded_route) {
                return  $next($request);
            }
        }

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
