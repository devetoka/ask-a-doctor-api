@extends('email.layouts.base')

@section('content')
This is the verification
    <p>token: {{$token}}</p>

@endsection
