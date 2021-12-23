@extends('layouts.app')

@section ('title', '出勤簿')

@section('content')
<attendance-show :user="{{ $user }}" :month="{{ json_encode($carbon->format('Y-m')) }}"
:days="{{ json_encode($days) }}" :attendances="{{ json_encode($attendances) }}"></attendance-show>
@endsection
