<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers; // Laravel xử lý login/logout

    public function showLoginForm()
    {
        return view('auth.login'); // Trả về view đăng nhập
    }

    protected function authenticated(Request $request, $user)
    {
        // Luôn chuyển hướng đến trang quản lý nhạc sau khi đăng nhập
        return redirect()->route('admin.dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->authenticated($request, Auth::user());
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('music.index');
    }
}
