@extends('layouts.app')

@section ('title', 'シフト申請')

@section('content')

<shift-create :date="{{ json_encode($date) }}"></shift-create>

@endsection
