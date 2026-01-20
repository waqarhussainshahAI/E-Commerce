@props(['categories'])

<div x-data="{
    tableRowData: @json($categories->items()),
    selectedRows: [],
    selectAll: false,

    handleSelectAll() {
        this.selectAll = !this.selectAll;
        this.selectedRows = this.selectAll ? this.tableRowData.map(row => row.id) : [];
    },

    handleRowSelect(id) {
        if (this.selectedRows.includes(id)) {
            this.selectedRows = this.selectedRows.filter(i => i !== id);
        } else {
            this.selectedRows.push(id);
        }
    },

    deleteRow(id) {
        if (!confirm('Are you sure you want to delete?')) return;

        fetch(`/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(() => {
            this.tableRowData = this.tableRowData.filter(row => row.id !== id);
        });
    }
}">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">

        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Categories</h3>

            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Filter
                </button>
                <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    See all
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-start text-gray-500 font-medium">ID</th>
                        <th class="px-6 py-3 text-start text-gray-500 font-medium">Name</th>
                        <th class="px-6 py-3 text-start text-gray-500 font-medium">Parent</th>
                        <th class="px-6 py-3 text-start text-gray-500 font-medium">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="row in tableRowData" :key="row.id">
                        <tr class="border-b border-gray-100 dark:border-white/[0.05]">

                            <!-- ID -->
                            <td class="px-6 py-3.5 text-gray-700 dark:text-gray-400" x-text="row.id"></td>

                            <!-- NAME -->
                            <td class="px-6 py-3.5 text-gray-700 dark:text-gray-400" x-text="row.name"></td>

                            <!-- PARENT -->
                            <td class="px-6 py-3.5 text-gray-700 dark:text-gray-400">
                                <span x-text="row.parent ? row.parent.name : '-'"></span>
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-3.5 flex gap-3">

                                <!-- Edit -->
                                <a :href="`/categories/${row.id}/edit`"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 4h10M11 4L4 11v9h9l7-7M18 13l-5-5" />
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <button @click="deleteRow(row.id)">
                                    <svg class="text-red-600 hover:text-red-800 size-5 dark:text-red-400 dark:hover:text-red-300"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                            </td>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
