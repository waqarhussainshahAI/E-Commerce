<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Register</title>

  {{-- Option 1: CDN (quick) --}}
  <script src="https://cdn.tailwindcss.com"></script>

 
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

  <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-semibold text-center mb-4 border-black-300">Create Admin Account</h1>

   
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.register.submit') }}" class="space-y-4">
      @csrf

      {{-- Name --}}
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input id="name" name="name" type="text" placeholder="Enter name here" value="{{ old('name') }}"
               class="mt-1 border-2 px-2 block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
               required autofocus>
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" placeholder="Enter email here" name="email" type="email" value="{{ old('email') }}"
               class="mt-1 px-2 block border-2 w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
               required>
        @error('email')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input placeholder="Enter password here"  id="password" name="password" type="password"
               class="mt-1 px-2 block w-full border-2 rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
               required>
        @error('password')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input placeholder="Enter confirm password here"  id="password_confirmation" name="password_confirmation" type="password"
               class="mt-1 px-2 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
               required>
      </div>

      <div>
        <button type="submit"
                class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow">
          Register Admin
        </button>
      </div>

      <div class="text-center text-sm text-gray-600">
        <a href="{{ route('admin.login') }}" class="text-indigo-600 hover:underline">Already have an account? Login</a>
      </div>

    </form>
  </div>

</body>
</html>
