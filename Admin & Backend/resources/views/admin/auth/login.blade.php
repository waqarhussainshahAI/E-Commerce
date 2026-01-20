<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

  <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    <h1 class="text-2xl font-bold text-center mb-6 text-gray-700">
      Admin Login
    </h1>
@if(session('error'))
    <p class="text-red-600 text-sm text-center mb-4">
        {{ session('error') }}
    </p>
@endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
        @csrf

        <div>
            <input 
                type="email"
                name="email"
                placeholder="Email"
                value="{{ old('email') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
            >
            @error('email') 
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <input 
                type="password" 
                name="password"
                placeholder="Password"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
            >
            @error('password') 
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
            @enderror

        </div>

        <div>
            <button 
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition shadow-md"
            >
                Login
            </button>
        </div>

        <p class="text-center text-gray-600 text-sm">
            Don't have an account?
            <a href="{{route('admin.register')}}" class="text-indigo-600 hover:underline">
                Register
            </a>
        </p>

        
            <a href="{{route('admin.password.forgot')}}" class="ttext-center text-sm  text-indigo-600 hover:underline">
                Forgot Password
            </a>
      

    </form>
  </div>

</body>
</html>
