<?php

namespace App\Http\Controllers;

use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use App\Models\Akreditasi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

class SubstandarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('perPage', 5);
        $search = $request->input('search'); // Ambil input pencarian

        if ($user->role === 'Prodi') {
            $sub_unit_id = session('sub_unit_id');

            if (!$sub_unit_id) {
                return redirect()->route('login')->withErrors('Sub Unit tidak ditemukan. Silakan login kembali.');
            }

            $sub_unit = Prodi::find($sub_unit_id);

            $unit = $sub_unit->unit;

            $akreditasi_aktif = $sub_unit->akreditasis()->where('status', 'aktif')->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;

            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)->get();

            $substandars = collect();
            if ($request->has('standar_id')) {
                $selectedStandarId = $request->standar_id;
                $substandars = Substandar::where('standar_id', $selectedStandarId)
                    ->when($search, function ($query, $search) {
                        return $query->where('nama_substandar', 'like', "%{$search}%");
                    })
                    ->orderBy('no_urut')
                    ->paginate($perPage);
            }

            $lastNumber = Substandar::where('standar_id', $request->standar_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.substandar', compact('unit', 'sub_unit', 'standars', 'substandars', 'nextNumber', 'selected_akreditasi_id', 'user', 'perPage'));
        } else {
            // Untuk role lainnya (Unit atau UNIV)
            $sub_units = $user->sub_units;
            $unit_ids = $sub_units->pluck('unit_id')->unique();
            $unit = Fakultas::whereIn('id', $unit_ids)->get();

            $selected_unit_id = $request->input('unit_id');
            $selected_sub_unit_id = $request->input('sub_unit_id');

            $akreditasi_aktif = Akreditasi::where('sub_unit_id', $selected_sub_unit_id)
                ->where('status', 'aktif')
                ->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;

            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)->get();

            $substandars = collect();
            if ($request->has('standar_id')) {
                $selectedStandarId = $request->standar_id;
                $substandars = Substandar::where('standar_id', $selectedStandarId)
                    ->when($search, function ($query, $search) {
                        return $query->where('nama_substandar', 'like', "%{$search}%");
                    })
                    ->orderBy('no_urut')
                    ->paginate($perPage);
            }

            $lastNumber = Substandar::where('standar_id', $request->standar_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.substandar', compact('unit', 'sub_units', 'standars', 'substandars', 'nextNumber', 'selected_akreditasi_id', 'user', 'perPage'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_substandar' => 'required|string|max:255',
            'standar_id' => 'required|exists:standar,id',
        ]);

        // Buat substandar baru
        Substandar::create([
            'no_urut' => $request->no_urut,
            'nama_substandar' => $request->nama_substandar,
            'standar_id' => $request->standar_id,
        ]);

        // Ambil standar dan pastikan akreditasi dan sub_unit tersedia
        $standar = Standar::find($request->standar_id);
        if (!$standar) {
            return back()->withErrors('Standar tidak ditemukan.');
        }

        $akreditasi = $standar->akreditasi;
        if (!$akreditasi) {
            return back()->withErrors('Akreditasi untuk standar ini tidak ditemukan.');
        }

        $sub_unit = $akreditasi->sub_unit; // Gunakan singular 'sub_unit'
        if (!$sub_unit) {
            return back()->withErrors('Sub unit untuk akreditasi ini tidak ditemukan.');
        }

        // Jika semua relasi valid, lakukan redirect
        return redirect()->route('substandar.index', [
            'akreditasi_id' => $akreditasi->id,
            'unit_id' => Standar::find($request->standar_id)->akreditasi->sub_unit->unit_id,
            'sub_unit_id' => $sub_unit->id,
            'standar_id' => $request->standar_id,
            'perPage' => $request->input('perPage', 5),
        ])->with('success', 'Substandar berhasil ditambahkan!');
    }


    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $substandar = Substandar::find($item['id']);
            $substandar->no_urut = $item['no_urut'];
            $substandar->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, Substandar $substandar)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_substandar' => 'required|string|max:255',
        ]);

        $substandar->update([
            'no_urut' => $request->no_urut,
            'nama_substandar' => $request->nama_substandar,
        ]);

        return redirect()->route('substandar.index', [
            'akreditasi_id' => $substandar->standar->akreditasi_id,
            'unit_id' => Standar::find($request->standar_id)->akreditasi->sub_unit->unit_id,
            'sub_unit_id' => $substandar->standar->akreditasi->sub_unit_id,
            'standar_id' => $substandar->standar_id,
            'perPage' => $request->input('perPage', 5),
        ])->with('success', 'Substandar berhasil diperbarui!');
    }

    public function destroy(Substandar $substandar, Request $request)
    {
        $akreditasi_id = $substandar->standar->akreditasi_id;
        $standar_id = $substandar->standar_id;
        $substandar->delete();

        return redirect()->route('substandar.index', [
            'akreditasi_id' => $akreditasi_id,
            'unit_id' => Standar::find($standar_id)->akreditasi->sub_unit->unit_id,
            'sub_unit_id' => Standar::find($standar_id)->akreditasi->sub_unit_id,
            'standar_id' => $standar_id,
            'perPage' => $request->input('perPage', 5),
        ])->with('success', 'Substandar berhasil dihapus!');
    }
}
