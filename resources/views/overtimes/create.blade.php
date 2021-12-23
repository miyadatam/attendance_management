@extends('layouts.app')

@section ('title', '残業申請')

@section('content')

<over-time-create :date="{{ json_encode($date) }}"
:start_at="{{ json_encode($start_at) }}"
:end_at="{{ json_encode($end_at) }}"></over-time-create>

@endsection
