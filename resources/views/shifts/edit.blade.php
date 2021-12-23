@extends('layouts.app')

@section ('title', 'シフト申請')

@section('content')

<shift-edit :date="{{ json_encode($date) }}" :pending_shift="{{ json_encode($shift) }}"></shift-edit>

@endsection
