<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use App\Models\Akreditasi;
use App\Models\DetailItem;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jika user role adalah Prodi, gunakan session
        if ($user->role === 'Prodi') {
            $prodi_id = session('prodi_id');

            if (!$prodi_id) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
            }

            $prodi = Prodi::find($prodi_id);

            if (!$prodi) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan.');
            }

            $fakultas = $prodi->fakultas;

            if (!$fakultas) {
                return redirect()->route('login')->withErrors('Fakultas tidak ditemukan untuk prodi ini.');
            }

            // Ambil akreditasi aktif
            $akreditasi_aktif = $prodi->akreditasis()->where('status', 'aktif')->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;

            // Ambil standar
            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)->get();

            // Ambil substandar jika ada standar yang dipilih
            $substandars = collect();
            if ($request->has('standar_id')) {
                $selectedStandarId = $request->standar_id;
                $substandars = Substandar::where('standar_id', $selectedStandarId)->get();
            }

            // Ambil detail jika ada substandar yang dipilih
            $details = collect();
            if ($request->has('substandar_id')) {
                $selectedSubstandarId = $request->substandar_id;
                $details = Detail::where('substandar_id', $selectedSubstandarId)
                    ->orderBy('no_urut')
                    ->get();
            }

            // Tentukan nomor urut berikutnya
            $lastNumber = Detail::where('substandar_id', $request->substandar_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.detail', compact('fakultas', 'prodi', 'standars', 'substandars', 'details', 'nextNumber', 'selected_akreditasi_id', 'user'));
        } else {
            // Untuk role lainnya (Fakultas atau UNIV)
            $prodis = $user->prodis;
            $fakultas_ids = $prodis->pluck('fakultas_id')->unique();
            $fakultas = Fakultas::whereIn('id', $fakultas_ids)->get();

            $selected_fakultas_id = $request->input('fakultas_id');
            $selected_prodi_id = $request->input('prodi_id');

            // Ambil akreditasi aktif
            $akreditasi_aktif = Akreditasi::where('prodi_id', $selected_prodi_id)
                ->where('status', 'aktif')
                ->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;

            // Ambil standar
            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)->get();

            // Ambil substandar jika ada standar yang dipilih
            $substandars = collect();
            if ($request->has('standar_id')) {
                $selectedStandarId = $request->standar_id;
                $substandars = Substandar::where('standar_id', $selectedStandarId)->get();
            }

            // Ambil detail jika ada substandar yang dipilih
            $details = collect();
            if ($request->has('substandar_id')) {
                $selectedSubstandarId = $request->substandar_id;
                $details = Detail::where('substandar_id', $selectedSubstandarId)
                    ->orderBy('no_urut')
                    ->get();
            }

            // Tentukan nomor urut berikutnya
            $lastNumber = Detail::where('substandar_id', $request->substandar_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.detail', compact('fakultas', 'prodis', 'standars', 'substandars', 'details', 'nextNumber', 'selected_akreditasi_id', 'user'));
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_detail' => 'required|string|max:255',
            'substandar_id' => 'required|exists:substandar,id',
        ]);

        Detail::create([
            'no_urut' => $request->no_urut,
            'nama_detail' => $request->nama_detail,
            'substandar_id' => $request->substandar_id,
        ]);

        return redirect()->route('detail.index', [
            'akreditasi_id' => $request->akreditasi_id,
            'fakultas_id' => Substandar::find($request->substandar_id)->standar->akreditasi->prodi->fakultas_id,
            'prodi_id' => Substandar::find($request->substandar_id)->standar->akreditasi->prodi_id,
            'standar_id' => $request->standar_id,
            'substandar_id' => $request->substandar_id
        ])->with('success', 'Detail berhasil ditambahkan!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $detail = Detail::find($item['id']);
            $detail->no_urut = $item['no_urut'];
            $detail->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, Detail $detail)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_detail' => 'required|string|max:255',
        ]);

        $detail->update([
            'no_urut' => $request->no_urut,
            'nama_detail' => $request->nama_detail,
        ]);

        return redirect()->route('detail.index', [
            'akreditasi_id' => $request->akreditasi_id,
            'fakultas_id' => Substandar::find($request->substandar_id)->standar->akreditasi->prodi->fakultas_id,
            'prodi_id' => Substandar::find($request->substandar_id)->standar->akreditasi->prodi_id,
            'standar_id' => $request->standar_id,
            'substandar_id' => $request->substandar_id
        ])->with('success', 'Detail berhasil diperbarui!');
    }

    public function destroy(Detail $detail)
    {
        $akrediatas_id = $detail->substandar->standar->akreditasi_id;
        $standar_id = $detail->substandar->standar_id;
        $substandar_id = $detail->substandar_id;
        $detail->delete();

        return redirect()->route('detail.index', [
            'substandar_id' => $substandar_id,
            'fakultas_id' => Substandar::find($substandar_id)->standar->akreditasi->prodi->fakultas_id,
            'prodi_id' => Substandar::find($substandar_id)->standar->akreditasi->prodi_id,
            'standar_id' => $standar_id,
            'akreditasi_id' => $akrediatas_id
        ])->with('success', 'Detail berhasil dihapus!');
    }

    public function showDetails($substandar_id)
    {
        $substandar = Substandar::findOrFail($substandar_id);

        // Ambil semua detail terkait substandar yang dipilih
        $details = $substandar->details()->with('items')->get();

        // Ambil semua detail terkait substandar yang dipilih
        $details = $substandar->details()->with(['items' => function ($query) {
            $query->orderBy('no_urut', 'asc'); // Urutkan berdasarkan no_urut
        }])->get();

        // Tentukan nomor urut berikutnya untuk setiap detail
        foreach ($details as $detail) {
            $detail->nextNoUrut = DetailItem::where('detail_id', $detail->id)->max('no_urut') + 1;
        }

        return view('pages.berkas.detail', compact('substandar', 'details'));
    }
}
