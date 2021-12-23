<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
  /**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
  public function authorize()
  {
    return true;
  }

  /**
  * Get the validation rules that apply to the request.
  *
  * @return array
  */
  public function rules()
  {
    $email_rule = $this->path() == '/register' ? 'unique:users' : 'unique:users,email,' . Auth::id() . ',id';
    $password_rule = $this->path() == '/register' ? 'required' : 'nullable';

    return [
      'username' => 'required|max:60',
      'email' => 'required|email|max:255|' . $email_rule,
      'password' => $password_rule . '|min:8|max:255|confirmed',
    ];
  }

  public function messages()
  {
    return [
      'required' => '入力必須項目です',
      'email' => 'メールアドレスの形式で入力',
      'unique' => '既に登録されたメールアドレスです',
      'min' => ':min文字以上で入力してください',
      'max' => '長すぎます',
      'confirmed' => 'パスワードが一致しません',
    ];
  }
}
