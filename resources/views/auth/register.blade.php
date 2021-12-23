@extends('layouts.app')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
      <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left p-5">
            <h1>
              <i class="mdi mdi-clock"></i> Work Stamp
            </h1>
            <form action="{{ route('register') }}" method="post" class="pt-3">
              @csrf
              <div class="form-group">
                @if($errors->has('username'))
                <small class="text-danger">{{ $errors->first('username') }}</small>
                @endif
                <input type="text" name="username" class="form-control form-control-lg" id="username" placeholder="ユーザー名" value="{{ old('username') }}">
              </div>
              <div class="form-group">
                @if($errors->has('email'))
                <small class="text-danger">{{ $errors->first('email') }}</small>
                @endif
                <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="メールアドレス" value="{{ old('email') }}">
              </div>
              <div class="form-group">
                @if($errors->has('password'))
                <small class="text-danger">{{ $errors->first('password') }}</small>
                @endif
                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="パスワード">
              </div>
              <div class="form-group">
                <input type="password" name="password_confirmation" class="form-control form-control-lg" id="password-confirm" placeholder="パスワード(再)">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
              </div>
              <div class="text-center mt-4 font-weight-light">アカウントをお持ちの方はこちら<a href="{{ route('login.form') }}" class="text-primary">こちら</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
