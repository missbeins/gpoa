<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    //protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect Super Admin Role, GPOA Admin and Adviser
        if ( ($user->roles->pluck('role'))->containsStrict('Super Admin') ) 
            return redirect()->route('admin.admin.home');
        elseif ( ($user->roles->pluck('role'))->containsStrict('GPOA Admin') ) 
            return redirect()->route('officer.officer.home');
        elseif ( ($user->roles->pluck('role'))->containsStrict('Adviser') ) 
            return redirect()->route('adviser.adviser.home');

        // User | President | Other Admins
        else
            abort(404);   
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
