@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold ">
                Order #{{ $order->id }}
            </h2>
            <div class="mt-4">
                <a href="{{ route('order.pdf', $order->id) }}"
                    class="text-blue-500 px-4 py-2 rounded hover:text-blue-600 hover:underline hover:cursor-pointer">Download
                    Receipt</a>
            </div>
        </div>

        <p><strong>Customer:</strong> {{ $order->orderAddress->full_name }}</p>
        <p><strong>Email:</strong> {{ $order->orderAddress->email }}</p>
        <p><strong>Delivery:</strong> {{ strtolower($order->status) }}</p>
        <p><strong>Payment:</strong> {{ strtolower($order->payment_status) }}</p>
        <p><strong>Address:</strong> {{ $order->orderAddress ? $order->orderAddress->getFullAddressAttribute() : 'N/A' }}
        </p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>

        <hr class="my-4">

        <h3 class="font-semibold mb-2">Order Items</h3>

        <table class="w-full border">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Product</th>
                    <th class="p-2">Qty</th>
                    <th class="p-2">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->OrderItem as $item)
                    <tr class="border-b">
                        <td class="p-2">{{ $item->product->name }}</td>
                        <td class="p-2 text-center">{{ $item->quantity }}</td>
                        <td class="p-2 text-center">${{ $item->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <h2 class="font-semibold mt-2 ">Total Amount: ${{ number_format($order->total_price, 2) }}</h2>
    @endsection
