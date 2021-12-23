@extends('layouts.app')

@section ('title', '打刻申請一覧')

@section('content')

<work-stamp-show :user="{{ $user }}" :date="{{ json_encode($date) }}"
:shift="{{ json_encode($shift) }}"
:active_start_work_stamp="{{ json_encode($active_start_work_stamp) }}"
:active_end_work_stamp="{{ json_encode($active_end_work_stamp) }}"
:not_active_workstamps="{{ $not_active_workstamps }}"
:is_pending_start_workstamp="{{ json_encode($is_pending_start_workstamp) }}"
:is_pending_end_workstamp="{{ json_encode($is_pending_end_workstamp) }}"></work-stamp-show>

@endsection
