<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Attendance;

class WorkStamp extends Model
{
  protected $fillable = [
    'attendance_id', 'before_work_stamp_id', 'is_edit', 'is_start',
    'stamp_at', 'exception', 'approve', 'memo'
  ];

  public function attendance(){
    return $this->belongsTo(Attendance::class);
  }
}
