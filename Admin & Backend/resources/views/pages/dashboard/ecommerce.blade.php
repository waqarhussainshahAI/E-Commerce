@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-ecommerce.ecommerce-metrics :total-orders="$totalOrders" :total-items="$totalItems" :customers="$customers" :earnings="$earnings"
                :delivered="$delivered" :pending-order="$pendingOrder" :cancel-order="$cancelOrder" />
            <x-ecommerce.monthly-sale />
        </div>
        <div class="col-span-12 xl:col-span-5">
            <x-ecommerce.monthly-target />
        </div>

        <div class="col-span-12">
            <x-ecommerce.statistics-chart />
        </div>

        <div class="col-span-12 xl:col-span-5">
            <x-ecommerce.customer-demographic />
        </div>

        <div class="col-span-12 xl:col-span-7">
            <x-ecommerce.recent-orders :recent-order="$recentOrder" />
        </div>
    </div>
@endsection
