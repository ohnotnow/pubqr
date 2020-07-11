<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactDetailsController extends Controller
{
    public function index()
    {
        abort_if(! auth()->user()->isSuperAdmin(), Response::HTTP_FORBIDDEN);

        return view('customers.index');
    }

    public function create()
    {
        return view('contact-details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'contact_number' => 'required|min:10',
            'contact_name' => 'required|min:2',
        ]);

        $request->session()->put('contact_number', $request->contact_number);
        $request->session()->put('contact_name', $request->contact_name);

        Customer::create([
            'contact_number' => encrypt($request->contact_number),
            'contact_name' => encrypt($request->contact_name),
        ]);

        return redirect(route('customer.home'));
    }
}
