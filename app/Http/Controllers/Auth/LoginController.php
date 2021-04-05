<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use URL;

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

    use AuthenticatesUsers {
        logout as performLogout;
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect( URL::to('/'));
    }

    
    protected function authenticated($request, $user)
    {
        if ($user->role = 'admin') {

            $this->redirectTo = route('home');

        } elseif ($user->role = 'customer') {

            $this->redirectTo = route('home');

        }elseif ($user->role = 'agent') {

            $this->redirectTo = route('home');

        }else{

            return $next($request);
        }

    }
}
