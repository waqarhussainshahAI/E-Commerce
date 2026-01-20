@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Order
            </h1>
        </div>
        <div class="mt-4 bg-white shadow rounded-2xl p-2">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Order List


                </h3>
                <div class="flex gap-2">
                    <div>
                        <form action="{{ route('order') }}" method="GET">
                            @csrf
                            <input name="search" value="{{ request('search') }}" type="text"
                                class="border border-black px-2 py-1 rounded"
                                placeholder="Search by user name or order num">
                            <button
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200">
                                Search
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('order') }}" method="GET">
                        <select
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200"
                            name="perPage" onchange="this.form.submit() ">
                            <option value="">Per Page</option>
                            <option value="1" {{ request('perPage') == '1' ? 'selected' : '' }}>1</option>
                            <option value="5" {{ request('perPage') == '5' ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('perPage') == '20' ? 'selected' : '' }}>20</option>
                            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </form>



                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs"">Order Id#</th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Customer </th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Total Items</th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Total Amount</th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs"> Order Date </th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Delivery</th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Payment</th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs"> Action </th>
                        <th class="px-6 py-3 text-left text-gray-500 text-theme-xs"> Download </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t hover:bg-gray-100 transition-colors">
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:underline">
                                    #{{ $order->id }}
                                </a>
                            </td>

                            <td class="px-4 py-2 text-center"> {{ $order->user->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $order->OrderItem->count() }}</td>
                            <td class="px-4 py-2 text-center">${{ $order->total_price }}</td>
                            <td class="px-4 py-2 text-center">{{ $order->created_at }}</td>
                            <td class="px-4 py-2 text-center">{{ strtolower($order->status) }}</td>
                            <td class="px-4 py-2 text-center">{{ strtolower($order->payment_status) }}</td>

                            <td class="px-4 py-2 text-center">
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status" onchange="this.form.submit()"
                                        class="rounded-lg  px-3 py-2 bg-amber-200">

                                        <option value="">Update Status</option>
                                        <option value="pending" {{ $order->status == 'PENDING' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="delivered" {{ $order->status == 'DELIVERED' ? 'selected' : '' }}>
                                            Delivered
                                        </option>
                                        <option value="cancel" {{ $order->status == 'CANCEL' ? 'selected' : '' }}>
                                            Cancel
                                        </option>

                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('order.pdf', $order->id) }}"
                                    class="text-blue-600 hover:underline hover:text-blue-800 hover:cursor-pointer">
                                    Download Receipt
                                </a>
                            </td>



                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
