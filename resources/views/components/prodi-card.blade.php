<div
    class="bg-white p-6 mb-6 shadow transition duration-300 group transform hover:-translate-y-2 hover:shadow-2xl rounded-2xl cursor-pointer border">
    <a target="_self" href="{{ route('prodi.login', $prodi->id) }}"
        class="absolute opacity-0 top-0 right-0 left-0 bottom-0"></a>
    <div class="relative mb-4 rounded-2xl">
        <img class="max-h-80 rounded-2xl w-full object-cover transition-transform duration-300 transform group-hover:scale-105"
            src="{{ $imagePath }}" alt="Prodi Image">

        <a class="flex justify-center items-center bg-red-700 bg-opacity-80 z-10 absolute top-0 left-0 w-full h-full text-white rounded-2xl opacity-0 transition-all duration-300 transform group-hover:scale-105 text-xl group-hover:opacity-100"
            href="{{ route('prodi.login', $prodi->id) }}" target="_self" rel="noopener noreferrer">
            Login
            <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    <h3 class="font-medium text-xl leading-8">
        <a href="{{ route('prodi.login', $prodi->id) }}"
            class="block relative group-hover:text-red-700 transition-colors duration-200 ">
            {{ $prodi->nama_prodi }}
        </a>
    </h3>
</div>
