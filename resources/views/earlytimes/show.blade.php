@extends('layouts.app')

@section ('title', '早出申請一覧')

@section('content')

<early-time-show :user="{{ $user }}" :date="{{ json_encode($date) }}"
:active_earlytime="{{ json_encode($active_earlytime) }}"
:not_active_earlytimes="{{ $not_active_earlytimes }}"
:is_pending_earlytime="{{ json_encode($is_pending_earlytime) }}"
:start_at="{{ json_encode($start_at) }}"
:end_at="{{ json_encode($end_at) }}"
></early-time-show>

@endsection
