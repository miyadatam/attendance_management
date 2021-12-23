@extends('layouts.app')

@section ('title', '打刻')

@section('content')

<work-stamp-stamping :shift="{{ json_encode($shift) }}" :pending_shift="{{ json_encode($pending_shift) }}"
:start_exists="{{ json_encode($start_exists) }}" :end_exists="{{ json_encode($end_exists) }}"></work-stamp-stamping>

@endsection
