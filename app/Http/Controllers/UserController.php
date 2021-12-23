<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Auth;

use App\Models\User;

class UserController extends Controller
{
  public function index(){

  }

  public function show($user_id = null){
    $user = $user_id ? User::findOrFail($user_id) : Auth::user();

    if($user_id && $user->id == Auth::id()){
      return redirect()->route('home');
    }elseif($user->id != Auth::id() && Auth::user()->role != 1){
      abort(404);
    }

    $title = $user->id == Auth::id() ? 'Home' : $user->username;

    return view('users.show', compact('user', 'title'));
  }

  public function edit(User $user){
    if(Auth::user()->role != 1 && $user->id != Auth::id()){
      abort(404);
    }

    $title = $user->id == Auth::id() ? 'Home' : $user->username;

    return view('users.edit', compact('user', 'title'));
  }

  public function update(UserRequest $request, User $user){
    if(Auth::user()->role != 1 && $user->id != Auth::id()){
      abort(404);
    }

    $user->fill($request->input())->save();

    return back();
  }

  public function destroy(User $user){
    if(Auth::user()->role != 1 || $user->id == Auth::id()){
      abort(404);
    }
    $user->delete();

    return back();
  }
}
