@extends('layouts.app')

@section ('title', '早出申請')

@section('content')

<early-time-edit :date="{{ json_encode($date) }}"
:start_at="{{ json_encode($start_at) }}"
:end_at="{{ json_encode($end_at) }}"
:pending_earlytime="{{ json_encode($earlytime) }}"></early-time-edit>

@endsection
