@extends('layouts.app')

@section ('title', '早出申請')

@section('content')

<early-time-create :date="{{ json_encode($date) }}"
:start_at="{{ json_encode($start_at) }}"
:end_at="{{ json_encode($end_at) }}"></early-time-create>

@endsection
