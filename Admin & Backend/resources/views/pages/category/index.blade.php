@extends('layouts.app')

@section('content')
    <div x-data="{
        openModal: {{ $errors->any() ? 'true' : 'false' }},
        openEdit: false,
        form: { name: '', slug: '' },
        editData: {},
    
        makeSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
        }
    }">


        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Categories</h1>
        </div>

        {{-- MAIN WRAPPER --}}
        <div class="space-y-6">

            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4
                    dark:border-white/[0.05] dark:bg-white/[0.03]">

                <!-- Header -->
                <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Categories List</h3>

                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('category') }}" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search for category" class="border border-black px-2 py-1 rounded">

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
                        <button @click="openModal = true"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3
                               text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]
                               dark:hover:text-gray-200">
                            Add New
                        </button>

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
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Parent</th>
                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Description</th>

                                <th class="px-6 py-3 text-left text-gray-500 text-theme-xs">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categories as $index => $category)
                                <tr class="border-b border-gray-100 dark:border-white/[0.05]">

                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $category->name }}
                                    </td>

                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $category->slug }}
                                    </td>

                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $category->parent->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-700 dark:text-gray-300 w-64" x-data="{ expanded: false }">

                                        <div :class="expanded ? '' : 'line-clamp-1'">
                                            {!! $category->description ?? '-' !!}
                                        </div>

                                        @if (strlen($category->description) > 50)
                                            <button @click="expanded = !expanded"
                                                class="text-blue-500 hover:underline text-sm mt-1">

                                                <span x-show="!expanded">See more</span>
                                                <span x-show="expanded">See less</span>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="px-6 py-3.5 flex gap-3">

                                        <!-- OPEN EDIT MODAL -->
                                        <button
                                            @click="
                                        openEdit = true;
                                        editData = {
                                            id: {{ $category->id }},
                                            name: '{{ $category->name }}',
                                            slug: '{{ $category->slug }}',
                                            description: `{{ $category->description }}`,
                                            parent_id: '{{ $category->parent_id }}'
                                        };
                                    "
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            ✏️
                                        </button>

                                        <!-- Delete -->
                                        <form action="{{ route('categories.delete', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button>
                                                <svg class="text-red-600 hover:text-red-800 size-5 dark:text-red-400 dark:hover:text-red-300"
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
                        {{ $categories->links() }}
                    </div>

                </div>

            </div>
        </div>

        <!-- ------------------------ -->
        <!--     ADD CATEGORY MODAL   -->
        <!-- ------------------------ -->
        <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="openModal = false">
        </div>

        <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div @click.stop class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-lg shadow-xl border">

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-semibold">Add Category</h1>
                    <button @click="openModal = false">✖</button>
                </div>

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <label>Name</label>
                    <input type="text" name="name" class="w-full mb-3 border px-2 py-1 rounded" x-model="form.name"
                        @input="form.slug = makeSlug(form.name)">

                    <label>Slug</label>
                    <input type="text" name="slug" class="w-full mb-3 border px-2 py-1 rounded" x-model="form.slug">



                    <div>
                        <label>Description</label>
                        <input class="w-full" id="description" type="hidden" name="description">
                        <trix-editor class="w-full mb-3 border px-2 py-1 rounded" input="description"></trix-editor>
                    </div>
                    <label>Parent Category</label>
                    <select name="parent_id" class="w-full mb-4 border px-2 py-1 rounded">
                        <option value="">Select Category</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>

                    <button class="w-full bg-indigo-600 text-white py-2 rounded">Create</button>
                </form>
            </div>
        </div>


        <!-- ------------------------ -->
        <!--     EDIT CATEGORY MODAL  -->
        <!-- ------------------------ -->
        <div x-show="openEdit" x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="openEdit = false"></div>

        <div x-show="openEdit" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div @click.stop class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-lg shadow-xl border">

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-semibold">Edit Category</h1>
                    <button @click="openEdit = false">✖</button>
                </div>

                <form :action="`/admin/categories/${editData.id}`" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="text" name="name" class="w-full mb-3 border px-2 py-1 rounded"
                        x-model="editData.name" @input="editData.slug = makeSlug(editData.name)">


                    <label>Slug</label>
                    <input type="text" name="slug" class="w-full mb-3 border px-2 py-1 rounded"
                        x-model="editData.slug">

                    <div>
                        <label>Description</label>
                        <input class="w-full" id="descriptions" type="hidden" name="description">
                        <trix-editor class="w-full mb-3 border px-2 py-1 rounded" x-model="editData.description"
                            input="descriptions"></trix-editor>
                    </div>


                    {{-- <label>Description</label>
                    <textarea name="description" class="w-full mb-3 border px-2 py-1 rounded" x-model="editData.description"></textarea> --}}

                    <label>Parent Category</label>
                    <select name="parent_id" class="w-full mb-4 border px-2 py-1 rounded" x-model="editData.parent_id">

                        <option value="">Select Category</option>

                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>

                    <button class="w-full bg-indigo-600 text-white py-2 rounded">Update</button>
                </form>

            </div>

        </div>

    </div>
@endsection
