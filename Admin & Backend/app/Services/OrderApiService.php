<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderNotification;
use App\Notifications\OrderPlacedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderApiService
{
    public function checkout($request)
    {
        Stripe::setApiKey(env('SECRET_KEY_STRIPE'));
        $paymentIntent = PaymentIntent::create([
            'amount' => 500, // dummy
            'currency' => 'usd',
            'metadata' => [
                'user_id' => $request->user()->id ?? null,
            ],
        ]);

        return $paymentIntent->client_secret;
    }

    public function proceedOrder($req, $userId)
    {

        $cart = Cart::where('user_id', $userId)->with('product')->get();

        if (! $cart || $cart->isEmpty()) {
            return response()->json(['message' => 'Cart not exist against this user'], 400);

        }

        $total = $cart->sum(fn ($item) => $item->product->price * $item->quantity
        );

        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $total,
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        foreach ($cart as $item) {
            $order->orderItem()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }
        $order->orderAddress()->create([
            'full_name' => $req->fullName,
            'email' => $req->email,
            'street_address' => $req->streetAddress,
            'city' => $req->city,
            'province' => $req->province,
            'zip_code' => $req->zipCode,
        ]);

        $order->load('user');
        $order->load('orderItem.product.primaryImage');
        $order->load('orderAddress');
        // Log::info('Order created: '.$order);
        Cart::where('user_id', $userId)->delete();
        auth()->user()->notify(new OrderPlacedNotification($order));

    }

    public function updateStatus($request, $order)
    {
        $order->update([
            'status' => strtoupper($request->status),
        ]);

        // send to curernt user /admin whih update
        // auth()->user()->notify( new OrderNotification($order));

        // send to other user
        $user = User::findorFail($order->user_id);
        $user->notify(new OrderNotification($order, $request->status));

    }

    public function allOrder($req)
    {
        $search = $req->input('search');

        // return [$req, $search];
        $page = $req->input('perPage') ?? 5;

        // $limit = $req->query('limit') || 5;
        $orders = Order::with('orderItem.product.primaryImage', 'user')
            ->when($search, function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->paginate($page)->appends(request()->query());

        return $orders;
    }

    public function generateOrderPdf($order)
    {
        $orderId = $order->id;

        $order = Order::with(['user', 'orderItem.product.primaryImage', 'orderAddress'])
            ->where('id', $orderId)
            ->firstOrFail();

        // Generate PDF using a view (you need to create this view)
        $pdf = PDF::loadView('pages.pdf.order-pdf', compact('order'));

        // Return the PDF as a download
        return $pdf->download('order_'.$order->id.'.pdf');
    }
}
