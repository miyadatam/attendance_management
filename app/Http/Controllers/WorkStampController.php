<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use App\Models\User;
use App\Models\WorkStamp;

class WorkStampController extends Controller
{
  public function show(User $user, $date = ''){
    if(Auth::user()->role != 1 && $user->id != Auth::id()){
      abort(404);
    }

    $carbon = $date ? new Carbon($date) : new Carbon();
    $date = $carbon->format('Y-m-d');

    $shift = $user->activeShifts()->where('date', $date)->first();

    $active_start_work_stamp = $user->activeStartWorkStamps()->where('date', $date)->first();
    $active_end_work_stamp = $user->activeEndWorkStamps()->where('date', $date)->first();

    $not_active_workstamps = $user->notActiveWorkStamps()->where('date', $date)->get();

    $is_pending_start_workstamp = $user->pendingStartWorkStamps()->where('date', $date)->exists();
    $is_pending_end_workstamp = $user->pendingEndWorkStamps()->where('date', $date)->exists();

    return view('workstamps.show', compact('user', 'date', 'shift', 'active_start_work_stamp', 'active_end_work_stamp', 'not_active_workstamps', 'is_pending_start_workstamp', 'is_pending_end_workstamp'));
  }
  public function create($date){
    $carbon = new Carbon($date);
    $date = $carbon->format('Y-m-d');

    $shift = Auth::user()->activeShifts()->where('date', $date)->firstOrFail();

    $is_pending_start_workstamp = Auth::user()->pendingStartWorkStamps()->where('date', $date)->exists();
    $is_pending_end_workstamp = Auth::user()->pendingEndWorkStamps()->where('date', $date)->exists();

    if(Auth::user()->id != Auth::id() || $is_pending_start_workstamp && $is_pending_end_workstamp){
      abort(404);
    }

    return view('workstamps.create', compact('date', 'shift', 'is_pending_start_workstamp', 'is_pending_end_workstamp'));
  }
  public function stamping(){
    $shift = Auth::user()->activeShifts()->where('date', date('Y-m-d'))->first();

    $pending_shift = Auth::user()->approvePendingShifts()->where('date', date('Y-m-d'))->first();

    $start_exists = Auth::user()->activeStartWorkStamps()->where('date', date('Y-m-d'))->exists();

    $end_exists = Auth::user()->activeEndWorkStamps()->where('date', date('Y-m-d'))->exists();

    return view('workstamps.stamping', compact('shift', 'pending_shift', 'start_exists', 'end_exists'));
  }
  public function store(Request $request){
    $attendance = Auth::user()->attendances()->where('date', $request->date)->first();

    $data = $request->input();
    $active_work_stamp = Auth::user()->activeWorkStamps()->where('is_start', $request->is_start)->where('date', $request->date)->first();
    if($active_work_stamp){
      $data['before_work_stamp_id'] = $active_work_stamp->id;
    }
    $data['attendance_id'] = $attendance->id;
    $data['is_edit'] = true;
    $data['approve'] = 0;

    WorkStamp::create($data);

    return redirect()->route('work_stamp.show', ['user' => Auth::id(), 'date' => $request->date]);
  }
  public function stampingStore(Request $request){
    $attendance = Auth::user()->attendances()->where('date', date('Y-m-d'))->firstOrFail();
    $data = $request->input();
    $data['attendance_id'] = $attendance->id;
    $data['stamp_at'] = $request->stamp_at ?? null;
    $data['is_start'] = $request->has('is_start') ? 1 : 0;
    $data['is_edit'] = false;
    $data['approve'] = 1;

    WorkStamp::create($data);

    return back();
  }
  public function edit(WorkStamp $work_stamp){
    if($work_stamp->approve != 0 || $work_stamp->attendance->user_id != Auth::id()){
      abort(404);
    }

    $date = $work_stamp->attendance->date->format('Y-m-d');

    $shift = Auth::user()->activeShifts()->where('date', $date)->firstOrFail();

    return view('workstamps.edit', compact('date', 'shift', 'work_stamp'));
  }
  public function update(Request $request, WorkStamp $work_stamp){
    if($work_stamp->approve != 0 || $work_stamp->attendance->user_id != Auth::id()){
      abort(404);
    }

    $work_stamp->fill($request->except('is_start'))->save();

    return redirect()->route('work_stamp.show', ['user' => Auth::id(), 'date' => $work_stamp->attendance->date->format('Y-m-d')]);
  }
  public function destroy(Request $request, WorkStamp $work_stamp){
    if($work_stamp->approve != 0 || $work_stamp->attendance->user_id != Auth::id()){
      abort(404);
    }

    $date = $work_stamp->attendance->date->format('Y-m-d');
    $work_stamp->delete();

    return back();
  }
  public function workStampApprove(Request $request, WorkStamp $work_stamp){
    if(Auth::user()->role != 1 || $work_stamp->attendance->user_id == Auth::id()){
      abort(404);
    }

    DB::beginTransaction();
    try{
      $approve = $request->has('ok') ? 1 : 2;
      if($approve == 1){
        $is_start = $request->is_start ? 1 : 0;
        $active_work_stamp = $work_stamp->attendance->user->activeWorkStamps()->where('is_start', $is_start)->where('date', $request->date)->first();

        if($active_work_stamp){
          $active_work_stamp->fill(['approve' => 2])->save();
        }
      }

      $work_stamp->fill(compact('approve'))->save();
      DB::commit();
      return back();
    }catch(\Exception $e){

      DB::rollback();
      return redirect()->route('home');
    }
  }
}
