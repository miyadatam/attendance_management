<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\user;
use App\Models\Shift;
use App\Models\WorkStamp;
use App\Models\Overtime;

class Attendance extends Model
{
  protected $fillable = [
    'user_id', 'date', 'is_absence', 'break_time','memo'
  ];

  protected $dates = ['date'];

  public function user(){
    return $this->belongsTo(User::class);
  }

  // シフト
  public function shifts(){
    return $this->hasMany(Shift::class);
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
    return $this->hasMany(WorkStamp::class);
  }
  public function activeStartWorkStamps(){
    return $this->workStamps()->where('is_start', true)->where('approve', 1);
  }
  public function activeEndWorkStamps(){
    return $this->workStamps()->where('is_start', false)->where('approve', 1);
  }
  // 早出
  public function earlytimes(){
    return $this->hasMany(Overtime::class)->where('is_early', true);
  }
  public function activeEarlytimes(){
    return $this->earlytimes()->where('approve', 1);
  }
  // 残業
  public function overtimes(){
    return $this->hasMany(Overtime::class)->where('is_early', false);
  }
  public function activeOvertimes(){
    return $this->overtimes()->where('approve', 1);
  }
}
