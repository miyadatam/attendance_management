<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Attendance;

class Shift extends Model
{
  protected $fillable = [
    'attendance_id', 'before_shift_id', 'is_edit', 'item',
    'start_at', 'end_at', 'approve', 'memo'
  ];

  public function attendance(){
    return $this->belongsTo(Attendance::class);
  }

}
