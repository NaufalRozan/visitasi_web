<?php

namespace App\Http\Controllers;

use App\Models\Akreditasi;
use App\Models\Fakultas;
use App\Models\Standar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StandarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang login

        // Jika user role adalah Prodi, gunakan session untuk menentukan prodi dan fakultas
        if ($user->role === 'Prodi') {
            $sub_unit_id = session('sub_unit_id');

            if (!$sub_unit_id) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
            }

            $sub_unit = Prodi::find($sub_unit_id);

            if (!$sub_unit) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan.');
            }

            $unit = $sub_unit->unit;

            // Ambil akreditasi aktif (status = 'aktif')
            $akreditasi_aktif = $sub_unit->akreditasis()->where('status', 'aktif')->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;
            $standars = collect();
            if ($selected_akreditasi_id) {
                $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)
                    ->orderBy('no_urut', 'asc')
                    ->get();
            }

            // Tentukan nomor urut berikutnya
            $lastNumber = Standar::where('akreditasi_id', $selected_akreditasi_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.standar', compact('unit', 'sub_unit', 'standars', 'selected_akreditasi_id', 'nextNumber', 'user'));
        } else {
            // Untuk role lainnya (Fakultas atau UNIV), tampilkan dropdown fakultas dan prodi
            $sub_units = $user->sub_units;
            $unit_ids = $sub_units->pluck('unit_id')->unique();
            $unit = Fakultas::whereIn('id', $unit_ids)->get();

            $selected_unit_id = $request->input('unit_id');
            $selected_sub_unit_id = $request->input('sub_unit_id');

            $akreditasi_aktif = null;
            $selected_akreditasi_id = null;

            if ($selected_sub_unit_id) {
                $akreditasi_aktif = Akreditasi::where('sub_unit_id', $selected_sub_unit_id)->where('status', 'aktif')->first();
            }

            if ($akreditasi_aktif) {
                $selected_akreditasi_id = $akreditasi_aktif->id;
            }

            $standars = collect();
            if ($selected_akreditasi_id) {
                $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)
                    ->orderBy('no_urut', 'asc')
                    ->get();
            }

            $lastNumber = Standar::where('akreditasi_id', $selected_akreditasi_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.standar', compact('unit', 'sub_units', 'standars', 'selected_akreditasi_id', 'nextNumber', 'user'));
        }
    }



    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_standar' => 'required|string|max:255',
            'akreditasi_id' => 'required|exists:akreditasi,id', // Validasi akreditasi_id
        ]);

        // Ambil akreditasi berdasarkan sub_unit_id yang dipilih di dropdown
        $akreditasi = Akreditasi::where('id', $request->input('akreditasi_id'))->first();

        if (!$akreditasi) {
            return redirect()->back()->withErrors('Akreditasi tidak valid.');
        }

        // Simpan data standar baru dengan nomor urut dari input user dan sub unit yang dipilih
        Standar::create([
            'no_urut' => $request->no_urut,
            'nama_standar' => $request->nama_standar,
            'akreditasi_id' => $akreditasi->id,
        ]);

        return redirect()->route('standar.index', [
            'akreditasi_id' => $request->akreditasi_id,
            'unit_id' => Prodi::find($akreditasi->sub_unit_id)->unit_id,
            'sub_unit_id' => $akreditasi->sub_unit_id,
        ])->with('success', 'Standar berhasil ditambahkan!');
    }


    public function updateOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            $standar = Standar::find($item['id']);
            $standar->no_urut = $item['no_urut'];
            $standar->save();
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Standar $standar)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_standar' => 'required|string|max:255',
        ]);

        $standar->update([
            'no_urut' => $request->no_urut,
            'nama_standar' => $request->nama_standar,
        ]);

        return redirect()->route(
            'standar.index',
            [
                'akreditasi_id' => $request->akreditasi_id,
                'unit_id' => Prodi::find($standar->akreditasi->sub_unit_id)->unit_id,
                'sub_unit_id' => $standar->akreditasi->sub_unit_id,
            ],
        )->with('success', 'Standar berhasil diperbarui!');
    }

    public function destroy(Standar $standar)
    {
        $akreditasi_id = $standar->akreditasi_id;
        $standar->delete();

        return redirect()->route('standar.index', [
            'unit_id' => Prodi::find($standar->akreditasi->sub_unit_id)->unit_id,
            'sub_unit_id' => $standar->akreditasi->sub_unit_id,
            'akreditasi_id' => $akreditasi_id,
        ])->with('success', 'Standar berhasil dihapus!');
    }
}
