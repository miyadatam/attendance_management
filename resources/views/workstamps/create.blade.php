@extends('layouts.app')

@section ('title', '打刻')

@section('content')

<work-stamp-create :date="{{ json_encode($date) }}" :shift="{{ json_encode($shift) }}"
:is_pending_start_workstamp="{{ json_encode($is_pending_start_workstamp) }}"
:is_pending_end_workstamp="{{ json_encode($is_pending_end_workstamp) }}"></work-stamp-create>

@endsection
