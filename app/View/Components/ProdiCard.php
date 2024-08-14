<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;

class ProdiCard extends Component
{
    public $prodi;
    public $imagePath;

    public function __construct($prodi)
    {
        $this->prodi = $prodi;

        // Tentukan path gambar relatif dari folder public
        $imagePath = 'img/prodi/prodi-' . $prodi->id . '.jpg';
        $fullImagePath = public_path($imagePath);

        // Debugging: Periksa apakah file ada dan tampilkan path
        if (File::exists($fullImagePath)) {
            $this->imagePath = asset($imagePath);
        } else {
            $this->imagePath = Vite::asset('resources/images/default.jpg');
        }
    }

    public function render()
    {
        return view('components.prodi-card', [
            'imagePath' => $this->imagePath,
        ]);
    }
}
