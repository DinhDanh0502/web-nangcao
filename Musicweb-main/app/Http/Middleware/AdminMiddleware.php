<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
     public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để truy cập khu vực quản trị!');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('music.index')->with('error', 'Bạn không có quyền truy cập khu vực quản trị!');
        }

        return $next($request);
    }
}