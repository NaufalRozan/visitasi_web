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
        $sub_unit_id = session('sub_unit_id');

        // Validasi apakah prodi_id ada di session
        if (!$sub_unit_id) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Ambil Prodi berdasarkan prodi_id dari session
        $sub_unit = Prodi::find($sub_unit_id);

        if (!$sub_unit) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan.');
        }

        // Ambil Fakultas terkait Prodi
        $unit = $sub_unit->unit;

        // Ambil semua akreditasi terkait Prodi
        $akreditasis = $sub_unit->akreditasis()->get();

        // Ambil Akreditasi aktif
        $akreditasi_aktif = $sub_unit->akreditasis()->where('status', 'aktif')->first();

        // Gunakan akreditasi aktif atau request akreditasi_id
        $selected_akreditasi_id = $request->input('akreditasi_id', $akreditasi_aktif ? $akreditasi_aktif->id : null);

        $standars = collect();
        if ($selected_akreditasi_id) {
            // Hitung jumlah item dokumen, url, image, dan video untuk setiap detail
            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)
                ->with(['details' => function ($query) {
                    $query->withCount([
                        'items as document_count' => function ($query) {
                            $query->where('tipe', 'Document');
                        },
                        'items as url_count' => function ($query) {
                            $query->where('tipe', 'URL');
                        },
                        'items as image_count' => function ($query) {
                            $query->where('tipe', 'Image');
                        },
                        'items as video_count' => function ($query) {
                            $query->where('tipe', 'Video');
                        }
                    ]);
                }])
                ->get();
        }

        return view('pages.resume.home', compact('unit', 'sub_unit', 'akreditasis', 'selected_akreditasi_id', 'standars'));
    }
}
