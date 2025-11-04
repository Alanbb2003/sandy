<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $input = $request->all();
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $email = User::where("email","=",$request->email)->first();
        if ($email == null) {
            alert()->error('Error!','Email belum terdaftar'); 
            return back();
        }
        else {
            if(auth()->attempt(['email'=>$input["email"],'password'=>$input["password"]])){
                if(auth()->user()->role == 'user'){
                    return redirect()->route('home.main');
                }
                else if(auth()->user()->role == 'admin'){
                    return redirect()->route('homeAdmin');
                }
            }
            else{
                alert()->error('Error!','Email atau password salah');
                return back();
                // return redirect()->route("login")->with("error",'Incorect email or password');
            } 
        }  
    }
}
