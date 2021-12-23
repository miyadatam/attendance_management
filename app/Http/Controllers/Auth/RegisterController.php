<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;

use App\Models\User;

class RegisterController extends Controller
{
  use RegistersUsers;

  public function __construct(){
    $this->middleware('regenerate.token');
  }

  public function register(UserRequest $request){
    try{
      $data = $request->input();
      $password = $data['password'];
      $data['password'] = bcrypt($password);

      User::create($data);

      return redirect()->route('login.form')
      ->with('email', $data['email'])->with('password', $password);

    }catch(\Excepton $e){

      return back();
    }
  }
}
