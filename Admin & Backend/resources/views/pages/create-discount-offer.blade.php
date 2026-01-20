@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create Discount Offer</h1>
        <form method="GET" action="{{ route('create-discount-offer') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                class="border border-black px-2 py-1 rounded  ">
            <button
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200">
                Search
            </button>
        </form>
        <form method="POST" action="{{ route('create-discount-offer.store') }}" class="mt-6">
            @csrf
            {{-- Product Selection --}}
            <div class="border rounded p-4 mb-6">
                <div class="flex items-center mb-4 gap-2">
                    <h2 class="font-semibold mr-auto">Select Products</h2>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" onclick="selectAll(this)">
                        Select All
                    </label>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    @foreach ($productList as $product)
                        <div class="flex items-center gap-4  border-b py-3">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox">

                            <div>
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600 ">
                                    ID: {{ $product->id }} | Price: ${{ $product->price }}
                                    <span class="  ml-4"
                                        :class="[
                                            {{ $product->discountOffers?->is_active ? "'text-green-600'" : "'text-red-600'" }}
                                        ]">
                                        {{ $product->discountOffers?->is_active ? 'Active Discount ' . $product->discountOffers->name : 'No Active Discount' }}
                                    </span>
                                </p>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Discount Details --}}
            <div class="border rounded p-4 mb-6">
                <h2 class="font-semibold mb-4">Discount Details</h2>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium">Offer Name</label>
                        <input type="text" placeholder="Summer Sale " name="name" value="{{ old('name') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Start Date</label>
                        <input type="date" name="starts_date" value="{{ old('starts_date', now()->toDateString()) }}"
                            class="w-full border rounded px-3 py-2">

                    </div>

                    <div>
                        <label class="block text-sm font-medium">End Date</label>
                        <input type="date" name="ends_date" value="{{ old('ends_date') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Discount Type</label>
                        <select name="type" id="discountType" onchange="toggleDiscountType()"
                            class="w-full border rounded px-3 py-2">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Price</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Discount Value</label>
                        <input type="number" name="value" value="{{ old('value') }}" placeholder="e.g. 10 or 100"
                            class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                Create Discount
            </button>
        </form>
    </div>

    <script>
        function selectAll(source) {
            document.querySelectorAll('.product-checkbox')
                .forEach(cb => cb.checked = source.checked);
        }

        function toggleDiscountType() {}
    </script>
@endsection
