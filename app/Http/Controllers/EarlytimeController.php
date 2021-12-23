<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use App\Models\User;
use App\Models\Overtime;

class EarlytimeController extends Controller
{
  public function show(User $user, $date = ''){
    if(Auth::user()->role != 1 && $user->id != Auth::id()){
      abort(404);
    }

    $carbon = $date ? new Carbon($date) : new Carbon();
    $date = $carbon->format('Y-m-d');

    $active_earlytime = $user->activeEarlytimes()->where('date', $date)->first();
    $not_active_earlytimes = $user->notActiveEarlytimes()->where('date', $date)->get();
    $is_pending_earlytime = $user->pendingEarlytimes()->where('date', $date)->exists();

    $start_work_stamp = $user->activeStartWorkStamps()->where('date', $date)->first();
    $end_work_stamp = $user->activeEndWorkStamps()->where('date', $date)->first();
    $shift = $user->activeShifts()->where('date', $date)->first();

    if(!$start_work_stamp){
      $start_at = null;
    }elseif($start_work_stamp->exception == 1){
      $start_at = substr($start_work_stamp->stamp_at, 0, 5).  '遅刻';
    }else{
      $start_at = substr($shift->start_at, 0, 5);
    }

    if(!$end_work_stamp){
      $end_at = null;
    }elseif($end_work_stamp->exception == 1){
      $end_at = substr($end_work_stamp->stamp_at, 0, 5) . '早退';
    }else{
      $end_at = substr($shift->end_at, 0, 5);
    }

    return view('earlytimes.show', compact(
      'user', 'date', 'start_at', 'end_at',
      'active_earlytime', 'not_active_earlytimes', 'is_pending_earlytime',
    ));
  }
  public function create($date){
    $carbon = new Carbon($date);
    $date = $carbon->format('Y-m-d');

    $shift = Auth::user()->activeShifts()->where('date', $date)->firstOrFail();
    $start_work_stamp = Auth::user()->activeStartWorkStamps()->where('date', $date)->firstOrFail();
    $end_work_stamp = Auth::user()->activeEndWorkStamps()->where('date', $date)->firstOrFail();
    $is_pending_earlytime = Auth::user()->pendingEarlytimes()->where('date', $date)->exists();

    if($is_pending_earlytime){
      abort(404);
    }

    if($start_work_stamp->exception == 1){
      $start_at = substr($start_work_stamp->stamp_at, 0, 5).  '(遅刻)';
    }else{
      $start_at = substr($shift->start_at, 0, 5);
    }

    if($end_work_stamp->exception == 1){
      $end_at = substr($end_work_stamp->stamp_at, 0, 5) . '(早退)';
    }else{
      $end_at = substr($shift->end_at, 0, 5);
    }

    return view('earlytimes.create', compact('date', 'start_at', 'end_at'));
  }
  public function store(Request $request){
    $attendance_id = Auth::user()->attendances()->where('date', $request->date)->value('id');
    $active_earlytime_id = Auth::user()->activeEarlytimes()->where('date', $request->date)->value('overtimes.id');

    $data = $request->only('start_at', 'end_at', 'memo');
    $data['is_early'] = true;
    $data['approve'] = 0;
    $data['before_overtime_id'] = $active_earlytime_id ?? null;
    $data['attendance_id'] = $attendance_id;

    Overtime::create($data);

    return redirect()->route('earlytime.show', ['user' => Auth::id(), 'date' => $request->date]);
  }
  public function edit(Overtime $earlytime){
    if($earlytime->attendance->user_id != Auth::id() || $earlytime->approve != 0){
      abort(404);
    }

    $date = $earlytime->attendance->date->format('Y-m-d');

    $shift = Auth::user()->activeShifts()->where('date', $date)->firstOrFail();
    $start_work_stamp = Auth::user()->activeStartWorkStamps()->where('date', $date)->firstOrFail();
    $end_work_stamp = Auth::user()->activeEndWorkStamps()->where('date', $date)->firstOrFail();

    if($start_work_stamp->exception == 1){
      $start_at = substr($start_work_stamp->stamp_at, 0, 5).  '(遅刻)';
    }else{
      $start_at = substr($shift->start_at, 0, 5);
    }

    if($end_work_stamp->exception == 1){
      $end_at = substr($end_work_stamp->stamp_at, 0, 5) . '(早退)';
    }else{
      $end_at = substr($shift->end_at, 0, 5);
    }

    return view('earlytimes.edit', compact('date', 'earlytime', 'start_at', 'end_at'));
  }
  public function update(Request $request, Overtime $earlytime){
    if($earlytime->approve != 0 || $earlytime->attendance->user_id != Auth::id()){
      abort(404);
    }

    $data = $request->only('start_at', 'end_at', 'memo');

    $earlytime->fill($data)->save();

    return redirect()->route('earlytime.show', ['user' => Auth::id(), 'date' => $earlytime->attendance->date->format('Y-m-d')]);
  }
  public function destroy(Request $request, Overtime $earlytime){
    if($earlytime->attendance->user_id != Auth::id() || $earlytime->approve != 0){
      abort(404);
    }

    $earlytime->delete();

    return back();
  }
  public function earlyTimeApprove(Request $request, Overtime $earlytime){
    if(Auth::user()->role != 1 || $earlytime->attendance->user_id == Auth::id()){
      abort(404);
    }
    DB::beginTransaction();
    try{
      $approve = $request->has('ok') ? 1 : 2;
      if($approve == 1){
        $active_earlytime = $earlytime->attendance->user->activeEarlytimes()->where('date', $request->date)->first();
        if($active_earlytime){
          $active_earlytime->fill(['approve' => 2])->save();
        }
      }

      $earlytime->fill(compact('approve'))->save();

      DB::commit();
      return redirect()->route('earlytime.show', ['user' => $earlytime->attendance->user_id, 'date' => $request->date]);
    }catch(\Exception $e){

      DB::rollback();
      return redirect()->route('home');
    }
  }
}
