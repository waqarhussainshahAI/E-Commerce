<x-mail::message>
    # Order Confirmed

    Hello **{{ $user->name }}**,

    Your order ** #{{ $order->id }}** with total price ** ${{ $order->total_price }} ** has been confirmed.
    <x-mail::panel>
        **Delivery Address:** {{ $order->orderAddress->getFullAddressAttribute() }}
    </x-mail::panel>
    <x-mail::table>
        | Product | Quantity | Price |
        |---------|---------|-------|
        @foreach ($order->orderItem ?? [] as $item)
            | {{ $item->product->name }} | {{ $item->quantity }} | ${{ $item->price }} |
        @endforeach
    </x-mail::table>

    <x-mail::button :url="url(env('CLIENT_URL') . '/cart')">
        View Order
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
