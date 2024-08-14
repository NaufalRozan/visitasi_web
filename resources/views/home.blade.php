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
    </style>
</head>

<body>
    <!-- Background tetap pada tempatnya dan tidak scroll -->
    <div class="bg-cover" style="background-image: url('{{ Vite::asset('resources/images/background.jpg') }}');"></div>

    <!-- Konten di atas background yang bisa di-scroll -->
    <div class="p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-center px-2 mx-auto">
            @foreach ($prodis as $prodi)
                <x-prodi-card :prodi="$prodi" />
            @endforeach
        </div>
    </div>
</body>

</html>
