@extends('layouts.app')

@section ('title', $title)

@section('content')
<user-show :title="{{ json_encode($title) }}" :user="{{ $user }}"></user-show>
@endsection
