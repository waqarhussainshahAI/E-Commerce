<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Import Auth

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function stats()
    {
        if (Auth::guard('admin')->check()) {

            $orderCount = Order::count();
            $totalItems = OrderItem::count();

            $customerCount = User::count();

            $totalEarnings = Order::sum('total_price');
            $deliveredOrder = Order::where('status', 'DELIVERED')->count();
            $pendingOrder = Order::where('status', 'PENDING')->count();
            $cancelOrder = Order::where('status', 'CANCELLED')->count();
            $recentOrder = OrderItem::select('id', 'order_id', 'product_id', 'quantity')
                ->with('product.primaryImage', 'order.user:id,name,email')->latest()->take(5)->get();

            // return response()->json([

            //     'totalOrders' => $orderCount,
            //     'totalItems' => $totalItems,
            //     'customers' => $customerCount,
            //     'earnings' => $totalEarnings,
            //     'delivered' => $deliveredOrder,
            //     'pendingOrder' => $pendingOrder,
            //     'cancelOrder' => $cancelOrder,
            //     'recentItemsOrder' => $recentOrder,
            // ]);

            // return $recentOrder;

            return view('pages.dashboard.ecommerce', [
                'title' => 'E-commerce Dashboard',
                'totalOrders' => $orderCount,
                'totalItems' => $totalItems,
                'customers' => $customerCount,
                'earnings' => $totalEarnings,
                'delivered' => $deliveredOrder,
                'pendingOrder' => $pendingOrder,
                'cancelOrder' => $cancelOrder,
                'recentOrder' => $recentOrder,
            ]);
        } else {
            return redirect()->route('admin.login');
        }
    }
}
