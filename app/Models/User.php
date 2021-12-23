<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Attendance;
use App\Models\WorkStamp;
use App\Models\Shift;

class User extends Authenticatable
{
  use Notifiable;
  use SoftDeletes;

  protected $fillable = [
    'line_user_id', 'line_img', 'username', 'email', 'password', 'role'
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];
  // 勤怠
  public function attendances(){
    return $this->hasMany(Attendance::class);
  }
  public function monthAttendances($month){
    return $this->attendances()
    ->with('activeShifts', 'activeStartWorkStamps', 'activeEndWorkStamps', 'activeEarlytimes', 'activeOvertimes')
    ->where('date', 'like', $month . '%')->get();
  }
  // シフト
  public function shifts(){
    return $this->hasManyThrough(Shift::class, Attendance::class);
  }
  public function activeShifts(){
    return $this->shifts()->where('approve', 1);
  }
  public function approvePendingShifts(){
    return $this->shifts()->where('approve', 0);
  }
  public function notActiveShifts(){
    return $this->shifts()->where('approve', '<>', 1)->oldest('approve')->latest('created_at');
  }
  // 打刻
  public function workStamps(){
    return $this->hasManyThrough(WorkStamp::class, Attendance::class);
  }
  public function activeWorkStamps(){
    return $this->workStamps()->where('approve', 1);
  }
  public function activeStartWorkStamps(){
    return $this->workStamps()->where('is_start', true)->where('approve', 1);
  }
  public function activeEndWorkStamps(){
    return $this->workStamps()->where('is_start', false)->where('approve', 1);
  }
  public function approvePendingWorkStamps(){
    return $this->workStamps()->where('approve', 0);
  }
  public function pendingStartWorkStamps(){
    return $this->approvePendingWorkStamps()->where('is_start', true)->where('approve', 0);
  }
  public function pendingEndWorkStamps(){
    return $this->approvePendingWorkStamps()->where('is_start', false)->where('approve', 0);
  }
  public function notActiveWorkStamps(){
    return $this->workStamps()->where('approve', '<>', 1)->oldest('approve')->latest('created_at');
  }
  // 早出
  public function earlytimes(){
    return $this->hasManyThrough(Overtime::class, Attendance::class)->where('is_early', true);
  }
  public function activeEarlytimes(){
    return $this->earlytimes()->where('approve', 1);
  }
  public function pendingEarlytimes(){
    return $this->earlytimes()->where('approve', 0);
  }
  public function notActiveEarlytimes(){
    return $this->earlytimes()->where('approve', '<>', 1)->latest('created_at');
  }
  // 残業
  public function overtimes(){
    return $this->hasManyThrough(Overtime::class, Attendance::class)->where('is_early', false);
  }
  public function activeOvertimes(){
    return $this->overtimes()->where('approve', 1);
  }
  public function pendingOvertimes(){
    return $this->overtimes()->where('approve', 0);
  }
  public function notActiveOvertimes(){
    return $this->overtimes()->where('approve', '<>', 1)->latest('created_at');
  }

}
