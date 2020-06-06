@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center min-h-screen py-2 bg-gray-50 sm:px-6 lg:px-8">
        <div class="absolute top-0 right-0 mt-4 mr-4">
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <div class="flex items-center justify-center">
            <div class="flex flex-col justify-around">
                <div class="space-y-6 mx-auto">
                    <div class="flex justify-center">
                    @livewire('open-close-toggle')
                    </div>
                    <div class="">
                        <svg class="w-1/4 h-1/4 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <h1 class="text-5xl font-extrabold tracking-wider text-center text-gray-600">
                        {{ config('app.name') }}
                    </h1>

                    <div class="flex justify-center">

                    <ul class="list-reset">
                        <li class="inline px-4">
                            <a href="{{ route('order.index') }}" class="font-medium text-gray-600 hover:text-gray-500 focus:outline-none focus:underline transition ease-in-out duration-150">Current Orders</a>
                        </li>
                        <li class="inline px-4">
                            <a href="{{ route('order.history') }}" class="font-medium text-gray-600 hover:text-gray-500 focus:outline-none focus:underline transition ease-in-out duration-150">Order History</a>
                        </li>
                        <li class="inline px-4">
                            <a href="{{ route('inventory.index') }}" class="font-medium text-gray-600 hover:text-gray-500 focus:outline-none focus:underline transition ease-in-out duration-150">Inventory</a>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
