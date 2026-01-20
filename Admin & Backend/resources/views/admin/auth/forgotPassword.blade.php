<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Resend Link</title>

  {{-- Option 1: CDN (quick) --}}
  <script src="https://cdn.tailwindcss.com"></script>

 
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

  <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-semibold text-center mb-4 border-black-300">Forgot Passwod</h1>

   
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif


<div >
<form action="{{ route('admin.password.resetLink') }}" method="POST">
    @csrf
    <input class=" px-2 py-1 border-2 border-solid rounded-full" type="email" name="email" placeholder="Enter your email" required>
    <button class="mt-2 p-4 bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition shadow-md " type="submit">Send Reset Link</button>
</form>
</div>




  </div>

</body>
</html>
