<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Auth;
use Illuminate\Support\Facades\Log;

class PassVariableAllView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $uid = Auth::id();
        $notices = Notification::where('uid', $uid)->distinct()->orderByDesc('id')->get();
        View::share('notices', $notices);
        return $next($request);
    }
}
