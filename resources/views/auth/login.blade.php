@extends('layouts.app')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
      <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left p-5">
            <h1 class="">
              <i class="mdi mdi-clock"></i> Work Stamp
            </h1>
            <form action="{{ route('login') }}" method="post" class="pt-3">
              @csrf
              @if(session('login_error'))
              <small class="text-danger">{{ session('login_error') }}</small>
              @endif
              <div class="form-group">
                <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="メールアドレス">
              </div>
              <div class="form-group">
                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="パスワード">
              </div>
              <div class="my-2  align-items-center">
                <div class="form-check">
                  <label class="form-check-label text-muted">
                    <input type="checkbox" name="remember" class="form-check-input"> ログイン情報を保持する <i class="input-helper"></i></label>
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
                </div>
                <div class="text-center mt-4 font-weight-light">アカウントをお持ちでない方は<a href="{{ route('register.form') }}" class="text-primary">こちら</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
