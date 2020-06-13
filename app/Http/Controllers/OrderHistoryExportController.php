<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;

class OrderHistoryExportController extends Controller
{
    public function show()
    {
        $writer = SimpleExcelWriter::streamDownload('order_history_' . now()->format('d-m-Y-H-i') . '.xlsx');
        $orders = Order::with('item', 'fulfiller')->orderBy('created_at')->get();
        $orders->each(function ($order) use ($writer) {
            $writer->addRow([
                'Item' => $order->item->name,
                'Quantity' => $order->quantity,
                'Price' => $order->price_in_pounds,
                'Contact' => $order->contact,
                'Fulfilled' => $order->is_fulfilled ? 'Y' : 'N',
                'Cancelled' => $order->is_cancelled ? 'Y' : 'N',
                'Processed By' => optional($order->fulfiller)->name,
                'Date' => $order->created_at->format('d/m/Y H:i'),
               ]);
        });
        return $writer->toBrowser();
    }
}
