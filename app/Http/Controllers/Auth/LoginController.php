<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withInput($request->only('email'))->withErrors([
                'email' => 'Email not found. Please register or check your email.',
            ]);
        }

        if (!$user->password) {
            return redirect()->back()->withInput($request->only('email'))->withErrors([
                'password' => 'Password is empty. Please set your password.',
            ]);
        }

        return redirect()->back()->withInput($request->only('email'))->withErrors([
            'password' => 'Incorrect password. Please try again.',
        ]);
    }

    /**
     * User logout.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}
