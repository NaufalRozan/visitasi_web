<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Prodi</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Login untuk Prodi: {{ $prodi->nama_prodi }}</h1>

        <div class="max-w-md mx-auto">
            <form method="POST" action="{{ route('prodi.login', $prodi->id) }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input id="email" type="email" name="email" class="w-full px-3 py-2 border rounded-lg" required autofocus>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input id="password" type="password" name="password" class="w-full px-3 py-2 border rounded-lg" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
