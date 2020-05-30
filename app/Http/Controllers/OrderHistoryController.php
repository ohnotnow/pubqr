<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index()
    {
        return view('order.history', [
            'orders' => Order::with('item')->orderByDesc('created_at')->get(),
        ]);
    }
}
