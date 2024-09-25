<div
    class="bg-white p-6 mb-6 shadow transition duration-300 group transform hover:-translate-y-2 hover:shadow-2xl rounded-2xl cursor-pointer border">
    <div class="relative mb-4 rounded-2xl">
        <img class="h-80 w-full rounded-2xl object-cover transition-transform duration-300 transform group-hover:scale-105"
            src="{{ $imagePath }}" alt="Prodi Image">

        <div class="flex justify-center items-center bg-red-700 bg-opacity-80 z-10 absolute top-0 left-0 w-full h-full text-white rounded-2xl opacity-0 transition-all duration-300 transform group-hover:scale-105 text-xl group-hover:opacity-100"
            onclick="openModal({{ $prodi->id }})" style="cursor: pointer;">
            Login
            <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
            </svg>
        </div>
    </div>
    <h3 class="font-medium text-xl leading-8">
        <a href="javascript:void(0);" onclick="openModal({{ $prodi->id }})"
            class="block relative group-hover:text-red-700 transition-colors duration-200 ">
            {{ $prodi->nama_sub_unit }}
        </a>
    </h3>
</div>
