<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Attendance;

class Overtime extends Model
{
  protected $fillable = [
    'attendance_id', 'before_overtime_id', 'is_early',
    'start_at', 'end_at', 'approve', 'memo'
  ];

  public function attendance(){
    return $this->belongsTo(Attendance::class);
  }
}
