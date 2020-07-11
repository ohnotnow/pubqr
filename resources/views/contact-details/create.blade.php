@extends('layouts.app')

@section('content')
<form action="{{ route('contact-number.store') }}" method="POST">
    @csrf
    <div class="flex flex-col justify-center min-h-screen py-2 bg-gray-50 sm:px-6 lg:px-8">
        <div class="container px-5 py-24 mx-auto flex">
            <div class="lg:w-1/3 md:w-1/2 bg-white rounded-lg p-8 flex flex-col md:mx-auto w-full mt-10 md:mt-0 relative z-10">
                <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Contact Details</h2>
                <p class="leading-relaxed mb-5 text-gray-600">
                    As part of the Test and Trace scheme we need to get a contact name and number from you. We will keep these
                    for 21 days and then delete them.<br>
                    If we discover someone has been infected we may contact you if you were here at around the same time.<br>
                    All details are encrypted while we store them.
                </p>
                <label for="contact_number" class="text-gray-700">Contact Number</label>
                <input id="contact_number" class="bg-white rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2 mb-4" type="text" name="contact_number" required>
                <label for="contact_name" class="text-gray-700">Contact Name</label>
                <input id="contact_name" class="bg-white rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2 mb-8" type="text" name="contact_name" required>
                <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Store My Details</button>
            </div>
        </div>
    </div>
</form>
@endsection