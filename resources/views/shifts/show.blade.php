@extends('layouts.app')

@section ('title', 'シフト申請一覧')

@section('content')

<shift-show :user="{{ $user }}" :date="{{ json_encode($date) }}" :active_shift="{{ json_encode($active_shift) }}"
:not_active_shifts="{{ $not_active_shifts }}" :is_pending_shift="{{ json_encode($is_pending_shift) }}"></shift-show>

@endsection
