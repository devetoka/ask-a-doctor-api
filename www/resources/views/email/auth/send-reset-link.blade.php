@extends('email.layouts.base')
@section('content')

    @component('mail::panel')
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <p>This password reset link will expire in {{$expiration}} minutes.</p>
        @component('mail::button', ['url' => $url])
            Reset Password
        @endcomponent
        <p>If you did not request a password reset, no further action is required.</p>

    @endcomponent




    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
    @endcomponent

@endsection

