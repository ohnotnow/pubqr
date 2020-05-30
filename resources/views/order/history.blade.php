@extends('layouts.app')

@section('content')
<div class="container mx-auto">

<h1 class="text-2xl font-bold tracking-wide pt-8 pb-8">Order History</h1>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th class="text-left pl-4">ID</th>
            <th class="text-left pl-4">Item</th>
            <th class="text-right pr-4">Cost</th>
            <th class="text-left pl-4">Complete</th>
            <th class="text-left pl-4">Contact</th>
            <th class="text-right pr-4">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
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
                    {{ $order->is_fulfilled ? 'Y' : 'N' }}
                </td>
                <td class="border px-4 py-2">
                    {{ $order->contact }}
                </td>
                <td class="border px-4 py-2 text-right">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
