@extends('layouts.auth')
@section('title', 'Reset password')

@section('content')
    <div>
        @livewire('auth.passwords.reset')
    </div>
@endsection
