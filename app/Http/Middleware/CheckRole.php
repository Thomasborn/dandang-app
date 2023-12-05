<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class CheckRole
{
    /**
     * The authenticated user.
     *
     * @var \App\Models\User
     */
    private $user;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->user = Auth::user();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$this->user) {
            return redirect('/login');
        }

        foreach ($roles as $role) {
            if (method_exists($this->user, 'hasRole') && $this->user->hasRole($role)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized for resource'], 403);
        // return response()->json(['error' => 'Unauthorized'], 403);
    }
}
