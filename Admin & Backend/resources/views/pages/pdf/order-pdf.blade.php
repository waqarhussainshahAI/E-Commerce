<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Order Details</h1>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Customer Name:</strong> {{ $order->orderAddress->full_name }}</p>
    <p><strong>Customer Email:</strong> {{ $order->orderAddress->email }}</p>
    <p><strong>Total Items:</strong> {{ $order->orderItem->count() }}</p>
    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
    <p><strong>Delivery Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Delivery Address: </strong>
        {{ $order->orderAddress ? $order->orderAddress->getFullAddressAttribute() : 'N/A' }}
    </p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
    <h2>Items:</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItem as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>Total Amount: ${{ number_format($order->total_price, 2) }}</h2>

</body>

</html>
