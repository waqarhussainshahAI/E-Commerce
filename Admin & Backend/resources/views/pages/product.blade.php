@extends('layouts.app')

@section('content')
    <div x-data="{
        openModal: {{ $errors->any() ? 'true' : 'false' }},
        openEdit: false,
        editData: {},
        newImagesPreview: [],
        removedImages: [],
        form: { name: '', slug: '' },
        makeSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
        }
    }">
        {{-- Page Title --}}
        <div class="mb-6 flex justify-between">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Products</h1>
            {{-- @if (session('success'))
                <div class="p-2 mb-2 ml-4 bg-green-600 text-xs text-white rounded-md">
                    {{ session('success') }}
                </div>
            @endif --}}
        </div>

        {{-- MAIN WRAPPER --}}
        <div class="space-y-6">

            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4
                    dark:border-white/[0.05] dark:bg-white/[0.03]">

                <!-- Header -->
                <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Product List


                    </h3>

                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('product') }}" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search product by name or category ..."
                                class="border border-black px-2 py-1 rounded">

                            <button
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200">
                                Search
                            </button>
                        </form>
                        <!-- <button
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Filter
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </button> -->

                        <!-- Add Modal Button -->
                        <button x-data
                            @click="
        @if ($parents->count() === 0) if (confirm('No category found. You must need to create a category. Do you want to create a new category?')) {
                window.location.href = '{{ route('category') }}';
            }
        @else
            openModal = true; @endif
    "
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-3
           text-theme-sm font-medium shadow-theme-xs
          
               bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-800 ">
                            Add New
                        </button>


                        <div>
                            <select id="sortDropdown"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200">
                                <option value="">Sort By</option>

                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                    Name (↑)
                                </option>

                                <option value="-name" {{ request('sort') == '-name' ? 'selected' : '' }}>
                                    Name (↓)
                                </option>

                                <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>
                                    Stock (↑)
                                </option>

                                <option value="-stock" {{ request('sort') == '-stock' ? 'selected' : '' }}>
                                    Stock (↓)
                                </option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>
                                    Price (↑)
                                </option>

                                <option value="-price" {{ request('sort') == '-price' ? 'selected' : '' }}>
                                    Price (↓)
                                </option>
                            </select>
                        </div>
                        <script>
                            document.getElementById('sortDropdown').addEventListener('change', function() {
                                const selected = this.value;
                                const url = new URL(window.location.href);

                                url.searchParams.set('sort', selected);

                                window.location = url.toString();
                            });
                        </script>


                    </div>
                </div>

                <!-- Table -->
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full">
                        <thead
                            class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50
                                   dark:border-white/[0.05] dark:bg-gray-900">

                            <tr>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">No</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Name</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Slug</th>

                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Category</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Description</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Stock</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Sales</th>

                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Price (Rp)</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Discount Offer</th>

                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr class="border-b border-gray-100 dark:border-white/[0.05]">

                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $index + 1 }}
                                    </td>



                                    <td class="py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-[50px] w-[50px] overflow-hidden rounded-md">
                                                <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image) : asset('storage/unnamed.jpg') }}"
                                                    class="w-14 h-14 rounded" />


                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                    {{ $product->name }}
                                                </p>

                                            </div>
                                        </div>
                                    </td>



                                    {{-- 
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $product->name }}
                                    </td> --}}
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $product->slug }}
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $product->category->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300 w-8" x-data="{ expanded: false }">

                                        <div :class="expanded ? '' : 'line-clamp-1'">
                                            {!! $product->description ?? '-' !!}
                                        </div>

                                        @if (strlen($product->description) > 50)
                                            <button @click="expanded = !expanded"
                                                class="text-blue-500 hover:underline text-sm mt-1">
                                                <span x-show="!expanded">See more</span>
                                                <span x-show="expanded">See less</span>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $product->stock }}
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $product->delivered_orders_count ?? 0 }}
                                    </td>

                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        <span
                                            class="{{ $product->product_discount_value !== $product->price ? 'line-through text-xs text-gray-400' : '' }}">
                                            {{ $product->price }}
                                        </span>

                                        @if ($product->product_discount_value !== $product->price)
                                            <span class=" text-black ml-2">
                                                {{ $product->product_discount_value }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $product->discountOffers?->is_active ? $product->discountOffers->name . ' (' . $product->discountOffers->value . ($product->discountOffers->type == 'percentage' ? '%' : '') . ')' : '-' }}

                                    <td class="px-6 py-3.5 flex gap-3">

                                        <!-- OPEN EDIT MODAL -->
                                        <button
                                            @click="
        openEdit = true;
        editData = {
            id: @js($product->id),
            name: @js($product->name),
            slug: @js($product->slug),
            stock: @js($product->stock),
            price: @js($product->price),
            description: @js($product->description),
            images: @js($product->images),
            category_id: @js($product->category_id),         
               new_images: [], 

        };
    "
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            ✏️
                                        </button>


                                        <!-- Delete -->
                                        <form action="{{ route('product.delete', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button>
                                                <svg class="text-red-600
                                            hover:text-red-800 size-5 dark:text-red-400 dark:hover:text-red-300"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>

            </div>
        </div>

        <!-- ------------------------ -->
        <!--     ADD product MODAL   -->
        <!-- ------------------------ -->
        <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="openModal = false">
        </div>

        <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div @click.stop class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-lg shadow-xl border">

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-semibold">Add Product</h1>
                    <button @click="openModal = false">✖</button>
                </div>
                @session('error')
                    <div class="text-red-600 text-sm mb-3">{{ $error }}</div>
                @endsession

                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Upload Image</label>
                    <input class="border rounded px-2 py-1  mb-3 w-full " type="file" name="images[]" multiple>
                    @error('images')
                        <div class="text-red-600 text-sm mb-3">{{ $message }}</div>
                    @enderror



                    <div class="mb-3 w-full flex flex-col ">
                        <div class="w-full  flex gap-2 ">
                            <label class="w-1/2 ">Name<span class="text-red-500">*</span></label>
                            <label class="w-1/2 ">Slug</label>

                        </div>

                        <div class="w-full  flex gap-2 mb-3">
                            <input type="text" name="name" class=" w-1/2 border   px-2 py-1 rounded"
                                x-model="form.name" @input="form.slug = makeSlug(form.name)"">
                            @error('name')
                                <div class="text-red-600 text-sm ">{{ $message }}</div>
                            @enderror
                            <input type="text" name="slug" class="w-1/2 border  px-2 py-1 rounded"
                                x-model="form.slug">

                            @error('slug')
                                <div class="text-red-600  text-sm ">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <div class="flex flex-row w-full gap-2">
                                <label class="w-1/2">Stock</label>
                                <label class="w-1/2">Price</label>

                            </div>

                            <div class="flex flex-row gap-2">
                                <input type="text" name="stock" class="w-1/2  border px-2 py-1 rounded"
                                    x-model="form.stock">
                                @error('stock')
                                    <div class="text-red-600 text-sm mb-3 ">{{ $message }} </div>
                                @enderror
                                <input type="text" name="price" class="w-1/2 border px-2 py-1 rounded"
                                    x-model="form.price">
                                @error('price')
                                    <div class="text-red-600 text-sm mb-3">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>




                    <div>
                        <label>Description</label>
                        <input class="w-full" id="description" type="hidden" name="description">
                        <trix-editor class="w-full mb-3 border px-2 py-1 rounded" input="description"></trix-editor>
                    </div>



                    {{-- <label>Description</label>
                    <textarea name="description" class="w-full mb-3 border px-2 py-1 rounded"></textarea> --}}
                    @error('description')
                        <div class="text-red-600 text-sm mb-3">{{ $message }}</div>
                    @enderror
                    <label>Category</label>
                    <select name="category_id" class="w-full mb-4 border px-2 py-1 rounded">
                        <option value="">Select Category</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-red-600 text-sm mb-3 ">{{ $message }} </div>
                    @enderror

                    <button class="w-full bg-indigo-600 text-white py-2 rounded">Create</button>
                </form>
            </div>
        </div>


        <!-- ------------------------ -->
        <!--     EDIT product MODAL  -->
        <!-- ------------------------ -->
        <div x-show="openEdit" x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="openEdit = false">
        </div>

        <div x-show="openEdit" x-transition class="fixed top-10 inset-0 z-50 flex items-center justify-center p-4">

            <div @click.stop class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-lg shadow-xl border">

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-semibold">Edit Category</h1>
                    <button @click="openEdit = false">✖</button>
                </div>

                <form :action="`/admin/product/${editData.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Existing Images -->
                    <label class="font-semibold block mb-1">Existing Images</label>

                    <div class="grid grid-cols-4 gap-3 mb-4 ">
                        <template x-if="editData.images && editData.images.length">
                            <template x-for="img in editData.images" :key="img.id">
                                <div
                                    class="relative group border border-black rounded-xl overflow-hidden shadow-sm bg-gray-50 dark:bg-gray-700">

                                    <img :src="`/storage/${img.image}`"
                                        class="h-12 w-16 object-cover transition group-hover:scale-105 duration-200">

                                    <button type="button"
                                        @click="removedImages.push(img.id);  editData.images = editData.images.filter(i => i.id !== img.id)"
                                        class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded-full shadow">
                                        ✕

                                    </button>

                                </div>
                            </template>
                        </template>
                    </div>


                    <!-- Upload New Images -->
                    <label class="font-semibold block mb-1">Add New Images</label>

                    <input type="file" name="new_images[]" class="hidden" id="uploadInput" multiple
                        @change="
        [...$event.target.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => newImagesPreview.push({ src: e.target.result, file });
            reader.readAsDataURL(file);
        });
">

                    <div class="border-2 border-dashed rounded-xl p-2 mb-4 text-center cursor-pointer 
            bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                        @click.prevent="document.getElementById('uploadInput').click()">

                        <p class="text-gray-500">Click to upload images</p>
                    </div>


                    <!-- Preview New Uploads -->
                    <div x-show="newImagesPreview.length" class="grid grid-cols-4 gap-3 mb-4">

                        <template x-for="(img, index) in newImagesPreview" :key="index">
                            <div
                                class="relative group border rounded-xl overflow-hidden shadow-sm bg-gray-50 dark:bg-gray-700">

                                <img :src="img.src"
                                    class="h-16 w-12 object-cover transition group-hover:scale-105 duration-200">

                                <button type="button" @click="newImagesPreview.splice(index, 1)"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded-full shadow">
                                    ✕
                                </button>

                            </div>
                        </template>

                    </div>
                    <input type="hidden" name="removed_images" :value="JSON.stringify(removedImages)">


                    <div class="mb-3 w-full flex flex-col ">
                        <div class="w-full  flex gap-2 ">
                            <label class="w-1/2 ">Name<span class="text-red-500">*</span></label>
                            <label class="w-1/2 ">Slug</label>

                        </div>

                        <div class="w-full  flex gap-2 mb-3">
                            <input type="text" name="name" class=" w-1/2 border   px-2 py-1 rounded"
                                x-model="editData.name" @input="editData.slug = makeSlug(editData.name)">
                            @error('name')
                                <div class="text-red-600 text-sm ">{{ $message }}</div>
                            @enderror
                            <input type="text" name="slug" class="w-1/2 border  px-2 py-1 rounded"
                                x-model="editData.slug">

                            @error('slug')
                                <div class="text-red-600  text-sm ">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <div class="flex flex-row w-full gap-2">
                                <label class="w-1/2">Stock</label>
                                <label class="w-1/2">Price</label>

                            </div>

                            <div class="flex flex-row gap-2">
                                <input type="text" name="stock" class="w-1/2  border px-2 py-1 rounded"
                                    x-model="editData.stock">
                                @error('stock')
                                    <div class="text-red-600 text-sm mb-3 ">{{ $message }} </div>
                                @enderror
                                <input type="text" name="price" class="w-1/2 border px-2 py-1 rounded"
                                    x-model="editData.price">
                                @error('price')
                                    <div class="text-red-600 text-sm mb-3">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div>
                        <label>Description</label>
                        <input class="w-full" id="descriptions" type="hidden" name="description">
                        <trix-editor class="w-full mb-3 border px-2 py-1 rounded" input="descriptions"
                            x-model="editData.description"></trix-editor>
                    </div>


                    {{-- <label>Description</label> --}}
                    {{-- <textarea name="description" class="w-full mb-3 border px-2 py-1 rounded" x-model="editData.description"></textarea> --}}

                    <label>Category</label>
                    <select name="category_id" class="w-full mb-4 border px-2 py-1 rounded"
                        x-model="editData.category_id">

                        <option value="">Select Category</option>

                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-red-600 text-sm mb-3">{{ $message }}</div>
                    @enderror

                    <button class="w-full bg-indigo-600 text-white py-2 rounded">Update</button>
                </form>

            </div>

        </div>

    </div>
@endsection
