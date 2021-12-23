@extends('layouts.app')

@section ('title', 'シフト申請')

@section('content')

<work-stamp-edit :date="{{ json_encode($date) }}" :shift="{{ json_encode($shift) }}" :pending_work_stamp="{{ json_encode($work_stamp) }}"></work-stamp-edit>

@endsection
