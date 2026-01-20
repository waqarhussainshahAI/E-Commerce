





<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reset Password</title>

  <script src="https://cdn.tailwindcss.com"></script>

 
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

  <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-semibold text-center mb-4 border-black-300">Reset Admin Password</h1>

   
  
    @if(session('error'))
      <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif


@if ($errors->any())
    <div style="color: red; margin-bottom: 10px;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label>New Password</label><br>
            <input class="px-4 py-1 border-2 border-solid rounded-md"  type="password" name="password" required>
        </div>

        <div>
            <label>Confirm Password</label><br>
            <input class="px-4 py-1 border-2 border-solid rounded-md" type="password" name="password_confirmation" required>
        </div>

        <button class="mt-2 p-4 bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition shadow-md" type="submit">Reset Password</button>
    </form>




  </div>

</body>
</html>
