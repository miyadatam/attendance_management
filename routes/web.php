<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['guest']], function(){
  Route::namespace('Auth')->group(function(){
    Route::get('/register', function(){ return view('auth.register'); })
    ->name('register.form');
    Route::post('/register', 'RegisterController@register')
    ->name('register');
    Route::get('/login', function(){ return view('auth.login'); })
    ->name('login.form');
    Route::post('/login', 'LoginController@login')
    ->name('login');
  });
  Route::fallback(function(){ return redirect()->route('login.form'); });
});

Route::group(['middleware' => ['auth']], function(){
  // ホーム
  Route::redirect('/', 'home');
  Route::get('/home', 'UserController@show')->name('home');
  // ユーザー
  Route::resource('user', 'UserController');
  // attendance
  Route::get('attendance/{user}/{year?}/{month?}', 'AttendanceController@show')
  ->where(['year' => '[0-9]{4}', 'month' => '0[1-9]|1[0-2]']);
  Route::resource('attendance', 'AttendanceController');
  // シフト
  Route::resource('shift', 'ShiftController', ['only' => ['store', 'edit', 'update', 'destroy']]);
  Route::post('shift/approve/{shift}', 'ShiftController@shiftApprove');
  Route::get('shift/create/{date}', 'ShiftController@create')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])']);
  Route::get('shift/{user}/{date?}', 'ShiftController@show')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])'])
  ->name('shift.show');
  // 打刻
  Route::resource('work_stamp', 'WorkStampController', ['only' => ['store', 'edit', 'update', 'destroy']]);
  Route::post('/work_stamp/approve/{work_stamp}', 'WorkStampController@workStampApprove');
  Route::get('work_stamp/create/{date}', 'WorkStampController@create')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])']);
  Route::get('work_stamp/stamping', 'WorkStampController@stamping');
  Route::post('work_stamp/stamping', 'WorkStampController@stampingStore');
  Route::get('/work_stamp/{user}/{date?}', 'WorkStampController@show')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])'])
  ->name('work_stamp.show');
  // 早出
  Route::resource('earlytime', 'EarlytimeController', ['only' => ['store', 'edit', 'update', 'destroy']]);
  Route::post('earlytime/approve/{earlytime}', 'EarlytimeController@earlyTimeApprove');
  Route::get('earlytime/create/{date}', 'EarlytimeController@create')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])']);
  Route::get('earlytime/{user}/{date?}', 'EarlytimeController@show')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])'])
  ->name('earlytime.show');
  // 残業
  Route::resource('overtime', 'OvertimeController', ['only' => ['store', 'edit', 'update', 'destroy']]);
  Route::post('overtime/approve/{overtime}', 'OvertimeController@overtimeApprove');
  Route::get('overtime/create/{date}', 'OvertimeController@create')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])']);
  Route::get('overtime/{user}/{date?}', 'OvertimeController@show')
  ->where(['date' => '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])'])
  ->name('overtime.show');

  // ログアウト
  Route::get('/logout', 'Auth\LoginController@logout');
  Route::fallback(function(){ return redirect()->route('user.show', Auth::id()); });
});
