<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;

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

    public function Login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if($validator->fails())
        {
            return ["status" => false, "validacao" => true,"erros" => $validator->errors()];
        }

        if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']]))
        {
            $user  = auth()->user();
            $user->image = asset($user->image);
            $user->token = $user->createToken($user->email)->accessToken;
            
            return ["status" => true, "usuario" => $user ];
            
        }
        else
        {
            return ["status" => false];
        }

    }
}
