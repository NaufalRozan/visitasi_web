<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodi Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Pastikan body memenuhi seluruh layar */
        body {
            margin: 0;
            height: 100%;
        }

        .bg-cover {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Style untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Background tetap pada tempatnya dan tidak scroll -->
    <div class="bg-cover" style="background-image: url('{{ Vite::asset('resources/images/background.jpg') }}');"></div>

    <!-- Konten di atas background yang bisa di-scroll -->
    <div class="p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-center px-2 mx-auto">
            @foreach ($prodis as $prodi)
                <div onclick="openModal({{ $prodi->id }})" class="cursor-pointer">
                    <x-prodi-card :prodi="$prodi" />
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 class="text-center mb-4">Login {{ $prodi->nama_prodi }}</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="hidden" name="prodi_id" id="prodi_id" value="">

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(prodiId) {
            document.getElementById('prodi_id').value = prodiId;
            document.getElementById('loginModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('loginModal').style.display = "none";
        }
    </script>
</body>

</html>
