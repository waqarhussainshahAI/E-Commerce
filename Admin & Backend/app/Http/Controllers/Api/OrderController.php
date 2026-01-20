<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProceedOrderDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProceedOrder;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderApiService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderApiService $orderApiService) {}

    public function checkout(Request $request)
    {

        $clientSecret = $this->orderApiService->checkout($request);

        return response()->json([
            'client_secret' => $clientSecret, 201,
        ]);
    }

    public function proceedOrder(ProceedOrder $req)
    {

        $dto = ProceedOrderDto::fromArray($req->validated());
        $userId = $req->user()->id;
        $this->orderApiService->proceedOrder($dto, $userId);

        return response()->json(['message' => 'Order Booked Successfully'], 201);

    }

    public function getMyOrderHistory(Request $req)
    {
        $orders = Order::with('orderItem.product.primaryImage')->where('user_id', $req->user()->id)->latest()->get();

        return OrderResource::collection($orders);
    }

    public function allOrder(Request $req)
    {
        $orders = $this->orderApiService->allOrder($req);

        return view('pages.order', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'OrderItem.product', 'orderAddress']); // eager load relations

        return view('pages.orderShow', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,delivered,cancel',
        ]);

        $this->orderApiService->updateStatus($request, $order);

        return back()->with('success', 'Order status updated');
    }

    public function generateOrderPdf(Order $order)
    {
        return $this->orderApiService->generateOrderPdf($order);
    }
}
