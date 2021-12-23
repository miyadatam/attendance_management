@extends('layouts.app')

@section ('title', '残業申請')

@section('content')

<over-time-edit :date="{{ json_encode($date) }}"
:start_at="{{ json_encode($start_at) }}"
:end_at="{{ json_encode($end_at) }}"
:pending_overtime="{{ json_encode($overtime) }}"></over-time-edit>

@endsection
