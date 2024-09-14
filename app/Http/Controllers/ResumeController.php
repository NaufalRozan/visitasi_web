<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Akreditasi;
use App\Models\Standar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prodi_id = session('prodi_id');

        // Validasi apakah prodi_id ada di session
        if (!$prodi_id) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Ambil Prodi berdasarkan prodi_id dari session
        $prodi = Prodi::find($prodi_id);
        if (!$prodi) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Ambil Fakultas terkait Prodi
        $fakultas = $prodi->fakultas;

        // Ambil semua akreditasi terkait Prodi
        $akreditasis = $prodi->akreditasis()->get();

        // Ambil Akreditasi aktif
        $akreditasi_aktif = $prodi->akreditasis()->where('status', 'aktif')->first();

        // Gunakan akreditasi aktif atau request akreditasi_id
        $selected_akreditasi_id = $request->input('akreditasi_id', $akreditasi_aktif ? $akreditasi_aktif->id : null);

        $standars = collect();
        if ($selected_akreditasi_id) {
            // Hitung jumlah dokumen berdasarkan tipe
            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)
                ->withCount(['details as document_count' => function ($query) {
                    $query->whereHas('items', function ($query) {
                        $query->where('tipe', 'Document');
                    });
                }, 'details as url_count' => function ($query) {
                    $query->whereHas('items', function ($query) {
                        $query->where('tipe', 'URL');
                    });
                }, 'details as image_count' => function ($query) {
                    $query->whereHas('items', function ($query) {
                        $query->where('tipe', 'Image');
                    });
                }, 'details as video_count' => function ($query) {
                    $query->whereHas('items', function ($query) {
                        $query->where('tipe', 'Video');
                    });
                }])
                ->get();
        }

        return view('pages.resume.home', compact('fakultas', 'prodi', 'akreditasis', 'selected_akreditasi_id', 'standars'));
    }
}
