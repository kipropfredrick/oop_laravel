<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use URL;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

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
//                     $credentials = [
//     'email'    => $user->email,
// ];

$user = Sentinel::findById($user->id);

Sentinel::login($user);
// Sentinel::activate($credentials);
//$res=Sentinel::authenticate($credentials);


            $this->redirectTo = route('dashboard');

        } elseif ($user->role = 'customer') {

            $this->redirectTo = route('dashboard');

        }elseif ($user->role = 'agent') {

            $this->redirectTo = route('dashboard');

        }else{

            return $next($request);
        }

    }
}
