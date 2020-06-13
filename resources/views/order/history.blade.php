@extends('layouts.app')

@section('content')
<div class="min-h-screen py-2 bg-gray-50 sm:px-6 lg:px-8">
<div class="container mx-auto">

<div class="flex justify-between items-center pb-8">
    <h1 class="text-2xl font-bold tracking-wide">Order History</h1>
    <a href="{{ route('order.history.export') }}" data-turbolinks="false" class="default-link">Export to Excel</a>
</div>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th class="text-left pl-4">ID</th>
            <th class="text-left pl-4">Item</th>
            <th class="text-right pr-4">Cost</th>
            <th class="text-left pl-4">Contact</th>
            <th class="text-left pl-4">Status</th>
            <th class="text-left pl-4">Processed By</th>
            <th class="text-right pr-4">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr class="hover:bg-gray-100">
                <td class="border px-4 py-2">
                    {{ $order->id }}
                </td>
                <td class="border px-4 py-2">
                    {{ $order->item->name }}
                </td>
                <td class="border px-4 py-2 text-right">
                    {{ $order->cost_in_pounds }}
                </td>
                <td class="border px-4 py-2">
                    {{ $order->contact }}
                </td>
                <td class="border px-4 py-2">
                    {{ $order->getStatus() }}
                </td>
                <td class="border px-4 py-2">
                    {{ optional($order->fulfiller)->name }}
                </td>
                <td class="border px-4 py-2 text-right">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
@endsection
