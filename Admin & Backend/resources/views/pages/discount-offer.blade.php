@extends('layouts.app')

@section('content')
    <div class="container mt-8 mx-auto px-4 bg-white py-6 rounded shadow">
        @session('error')
            <div class=" border bg-red-400 rounded-md  text-white font-semibold text-md w-fit px-2 py-1  my-2 ml-auto   mb-4">
                {{ session('error') }} <span class="text-black">X</span>
            </div>
        @endsession
        <div class="flex items-center gap-2 mb-4 ">
            <h1 class="text-2xl font-bold mb-4 mr-auto">Discount Offers</h1>


            <a href="{{ route('create-discount-offer') }}"
                class=" text-black px-4 py-2 rounded border border-gray-300 hover:cursor-pointer">
                Create New Discount Offer </a>
            <a href="{{ route('discounts.deactivateAll') }}"
                class=" text-red-500 px-4 py-2  border rounded border-gray-300 hover:cursor-pointer">
                Deactivate All Discount Offer </a>
            <form method="GET" action ="{{ route('discounts') }}">
                <select name = "perPage" onChange = "this.form.submit()" class=" border border-gray-300 rounded px-2 py-1">
                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>

        </div>
        <table class="table-auto w-full mt-4">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Offer Name</th>
                    <th class="px-4 py-2">Product</th>
                    <th class="px-4 py-2">Price</th>

                    <th class="px-4 py-2">Discount Value</th>

                    <th class="px-4 py-2">Valid From</th>
                    <th class="px-4 py-2">Valid To</th>
                    <th class="px-4 py-2">Active</th>

                    <th class=" px-4 py-2">Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($discountedOffers ?? [] as $offer)
                    <tr class="border-b border-gray-100">
                        <td class=" px-4 py-2">{{ $offer->id }}</td>
                        <td class=" px-4 py-2">{{ $offer->name }}</td>

                        <td class=" px-4 py-2">{{ $offer->discountable->name }}</td>
                        <td class=" px-4 py-2 flex gap-2 items-center">
                            <span class=" text-red-500 line-through text-xs">${{ $offer->discountable->price }}</span>
                            <span class="text-green-500">${{ $offer->discountable->getProductAllDiscounts() }}</span>

                        </td>

                        <td class=" px-4 py-2">
                            {{ $offer->type === 'fixed' ? '$' . $offer->value : $offer->value . '%' }}</td>

                        <td class=" px-4 py-2">{{ $offer->starts_at ? $offer->starts_at->toFormattedDateString() : '-' }}
                        </td>

                        <td class=" px-4 py-2">{{ $offer->ends_at ? $offer->ends_at->toFormattedDateString() : '-' }}</td>

                        <td class=" px-4 py-2">

                            {{-- {{ $offer->is_active ? 'Yes' : 'No' }} --}}
                            <form method="POST" action="{{ route('discounts.toggleStatus', $offer->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center cursor-pointer group focus:outline-none">
                                    <div
                                        class="relative w-12 h-6 transition-colors duration-200 ease-in-out rounded-full {{ $offer->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                        <div
                                            class="absolute top-1 left-1 w-4 h-4 transition-transform duration-200 ease-in-out transform bg-white rounded-full {{ $offer->is_active ? 'translate-x-6' : 'translate-x-0' }}">
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </button>
                            </form>


                        </td>

                        <td class=" px-4 py-2 flex gap-2">
                            {{-- activate or deactive  --}}

                            {{-- edit --}}
                            <a href="{{ route('discounts.edit', $offer->id) }}"
                                class=" text-black px-2 py-1 hover:underline rounded hover:cursor-pointer">
                                Edit </a>
                            {{-- delete --}}
                            <a href="{{ route('discounts.delete', $offer->id) }}"
                                class=" text-red-500 px-2 py-1 hover:underline rounded hover:cursor-pointer">
                                Delete
                            </a>

                        </td>



                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $discountedOffers->links() }}


    </div>
@endsection
