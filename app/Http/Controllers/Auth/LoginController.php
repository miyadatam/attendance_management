<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
  use AuthenticatesUsers;

  public function __construct(){
    $this->middleware('regenerate.token');
  }

  public function login(Request $request){
    $data = $request->only('email', 'password');
    $remember = $request->filled('remember');
    if(Auth::attempt($data, $remember)){
      return redirect()->route('home');
    }

    return back()->with('login_error', 'メールアドレスまたはパスワードが違います。')->withInput();
  }

  public function logout(){
    Auth::logout();
    return redirect()->route('login.form');
  }
}
