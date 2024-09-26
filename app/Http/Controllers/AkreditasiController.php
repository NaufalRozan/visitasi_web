<?php

namespace App\Http\Controllers;

use App\Models\Akreditasi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class AkreditasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang login
        $perPage = $request->input('perPage', 5); // Default jumlah row per halaman adalah 5
        $search = $request->input('search'); // Ambil input pencarian

        if ($user->role === 'Prodi') {
            // Ambil prodi_id dari session jika user adalah Prodi
            $sub_unit_id = session('sub_unit_id');

            if (!$sub_unit_id) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
            }

            $sub_unit = Prodi::find($sub_unit_id);
            $unit = $sub_unit->unit;

            // Menambahkan fitur pencarian untuk Akreditasi berdasarkan nama
            $akreditasis = Akreditasi::where('sub_unit_id', $sub_unit->id)
                ->when($search, function ($query, $search) {
                    return $query->where('nama_akreditasi', 'like', "%{$search}%");
                })
                ->paginate($perPage);

            return view('pages.master.akreditasi', compact('unit', 'sub_unit', 'akreditasis', 'user', 'perPage'));
        } else {
            // Jika user role lain selain Prodi (misalnya Universitas atau Fakultas)
            $sub_units = $user->sub_units;
            $unit_ids = $sub_units->pluck('unit_id')->unique();
            $unit = Fakultas::whereIn('id', $unit_ids)->get();

            $selected_unit_id = $request->input('unit_id');
            $selected_sub_unit_id = $request->input('sub_unit_id');

            $sub_unit = null; // Default value null

            if ($selected_sub_unit_id) {
                $sub_unit = Prodi::find($selected_sub_unit_id); // Ambil prodi yang sesuai
                $akreditasis = Akreditasi::where('sub_unit_id', $selected_sub_unit_id)
                    ->when($search, function ($query, $search) {
                        return $query->where('nama_akreditasi', 'like', "%{$search}%");
                    })
                    ->paginate($perPage);
            } else {
                $akreditasis = collect(); // Kosongkan jika belum ada prodi yang dipilih
            }

            return view('pages.master.akreditasi', compact('unit', 'sub_units', 'akreditasis', 'user', 'sub_unit', 'perPage'));
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_akreditasi' => 'required|string|max:255',
            'sub_unit_id' => 'required|exists:sub_units,id',
        ]);

        Akreditasi::create([
            'nama_akreditasi' => $request->nama_akreditasi,
            'sub_unit_id' => $request->sub_unit_id,
            'status' => 'tidak aktif', // Atur status default
        ]);

        return redirect()->route('akreditasi.index', [
            'sub_unit_id' => $request->sub_unit_id,
            'unit_id' => Prodi::find($request->sub_unit_id)->unit_id,
            'perPage' => $request->input('perPage', 5),
        ])->with('success', 'Akreditasi berhasil ditambahkan!');
    }


    public function update(Request $request, Akreditasi $akreditasi)
    {
        $request->validate([
            'nama_akreditasi' => 'required|string|max:255',
        ]);

        $akreditasi->update([
            'nama_akreditasi' => $request->nama_akreditasi,
        ]);

        return redirect()->route(
            'akreditasi.index',
            [
                'sub_unit_id' => $akreditasi->sub_unit_id,
                'unit_id' => Prodi::find($akreditasi->sub_unit_id)->unit_id,
                'perPage' => $request->input('perPage', 5),
            ]
        )->with('success', 'Akreditasi berhasil diperbarui!');
    }

    public function activate(Akreditasi $akreditasi, Request $request)
    {
        // Nonaktifkan semua akreditasi pada sub_unit yang sama
        Akreditasi::where('sub_unit_id', $akreditasi->sub_unit_id)
            ->update(['status' => 'tidak aktif']);

        // Aktifkan akreditasi yang dipilih
        $akreditasi->update(['status' => 'aktif']);

        return redirect()->route(
            'akreditasi.index',
            [
                'sub_unit_id' => $akreditasi->sub_unit_id,
                'unit_id' => Prodi::find($akreditasi->sub_unit_id)->unit_id,
                'perPage' => $request->input('perPage', 5),
            ]
        )->with('success', 'Akreditasi berhasil diaktifkan!');
    }


    public function destroy(Akreditasi $akreditasi, Request $request)
    {
        $akreditasi->delete();

        return redirect()->route('akreditasi.index', [
            'sub_unit_id' => $akreditasi->sub_unit_id,
            'unit_id' => Prodi::find($akreditasi->sub_unit_id)->unit_id,
            'perPage' => $request->input('perPage', 5),
        ])->with('success', 'Akreditasi berhasil dihapus!');
    }
}
