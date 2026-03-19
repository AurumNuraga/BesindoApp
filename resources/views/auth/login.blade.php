<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Besindo</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold text-gray-800">Login Besindo</h1>
      <p class="text-gray-500 text-sm"></p>
    </div>

    <form action="{{ route('login.process') }}" method="POST">
      @csrf <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" 
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
        
        @error('email')
          <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="mb-6">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input type="password" name="password" id="password" 
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="flex items-center justify-between">
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
          Sign In
        </button>
      </div>
    </form>
    
    <p class="text-center text-gray-400 text-xs mt-6">
      &copy;2026 Besindo Project. All rights reserved.
    </p>
  </div>

</body>
</html>