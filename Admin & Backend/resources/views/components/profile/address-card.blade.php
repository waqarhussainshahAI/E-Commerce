<div x-data="{ open: {{ $errors->any() || session('error') ? 'true' : 'false' }} }">
    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
        Update Password
    </h4>
    @session('error')
        <p class="text-red-600 text-sm mt-1">{{ session('error') }}</p>
    @endsession
    @session('success')
        <p class="text-green-600 text-sm mt-1">{{ session('success') }}</p>
    @endsession
    <button x-show="!open" @click="open = true" type="button"
        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
        Change Current Password
    </button>
    <div x-show="open">
        <form class="flex flex-col" method="POST" action="{{ route('admin.updatePassword') }}">
            @csrf

            <div class="px-2 overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">

                    <div>
                        <label class="mb-1.5 block text-sm font-medium">Current Password</label>
                        <input type="password" name="current_password" class="h-11 w-full rounded-lg border px-4 py-2.5"
                            required>
                        @error('current_password')
                            <p class=" w-full text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="mb-1.5 block text-sm font-medium">New Password</label>
                        <input type="password" name="new_password" class="h-11 w-full rounded-lg border px-4 py-2.5"
                            required> @error('new_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium">Confirm Password</label>
                        <input type="password" name="new_password_confirmation"
                            class="h-11 w-full rounded-lg border px-4 py-2.5" required>
                        @error('new_password_confirmation')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 lg:justify-end">
                <button @click="open = false" type="button" class="rounded-lg border px-4 py-2.5">
                    Close
                </button>

                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-white">
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</div>
