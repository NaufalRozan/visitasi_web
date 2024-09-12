<?php

namespace App\Http\Controllers;

use App\Models\DetailItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DetailItemController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'detail_id' => 'required|exists:detail,id',
            'no_urut'   => 'required|integer',
            'deskripsi' => 'required|string|max:255',
            'lokasi'    => 'nullable|string|max:255', 
            'file_upload' => 'nullable|file|max:2048', // Validasi untuk file (max size 2MB)
            'tipe'      => 'required|string|max:255',
        ]);

        // Handle file upload jika ada file yang diunggah
        $lokasi = null; // Inisialisasi lokasi

        if ($request->hasFile('file_upload')) {
            // Simpan file di 'public/documents' dan dapatkan path-nya
            $filePath = $request->file('file_upload')->store('public/documents');
            $lokasi = str_replace('public/', 'storage/', $filePath); // Ganti jalur agar sesuai dengan public path
        } else {
            $lokasi = $request->input('lokasi'); // Jika bukan file, simpan URL atau path lainnya
        }

        // Simpan data ke tabel detail_item
        DetailItem::create([
            'detail_id' => $request->input('detail_id'),
            'no_urut'   => $request->input('no_urut'),
            'deskripsi' => $request->input('deskripsi'),
            'lokasi'    => $lokasi, // Bisa berupa path file atau URL
            'tipe'      => $request->input('tipe'),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'file_upload' => 'nullable|file|max:2048', // Validasi untuk file (max size 2MB)
        ]);

        $detailItem = DetailItem::findOrFail($id);

        // Jika file baru diunggah, ganti file yang lama
        if ($request->hasFile('file_upload')) {
            if ($detailItem->lokasi && Storage::exists(str_replace('storage/', 'public/', $detailItem->lokasi))) {
                Storage::delete(str_replace('storage/', 'public/', $detailItem->lokasi)); // Hapus file lama
            }
            $filePath = $request->file('file_upload')->store('public/documents');
            $lokasi = str_replace('public/', 'storage/', $filePath); // Ganti jalur agar sesuai dengan public path
        } else {
            $lokasi = $request->input('lokasi'); // Jika tidak ada file baru, gunakan lokasi yang ada
        }

        // Update data di database
        $detailItem->update([
            'deskripsi' => $request->deskripsi,
            'lokasi' => $lokasi, // Simpan lokasi baru jika ada
            'tipe' => $request->tipe,
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diperbarui!');
    }

    public function download($id)
    {
        $detailItem = DetailItem::findOrFail($id);

        // Dapatkan path file sebenarnya
        $filePath = public_path($detailItem->lokasi);

        // Pastikan file benar-benar ada
        if (file_exists($filePath)) {
            // Ambil ekstensi dari file yang ada
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Buat nama file berdasarkan deskripsi, tambahkan ekstensi file yang benar
            $fileName = $detailItem->deskripsi . '.' . $extension;

            // Gunakan response untuk mengunduh file dengan nama yang disesuaikan
            return response()->download($filePath, $fileName);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy($id)
    {
        $detailItem = DetailItem::findOrFail($id);

        if ($detailItem->lokasi && Storage::exists(str_replace('storage/', 'public/', $detailItem->lokasi))) {
            Storage::delete(str_replace('storage/', 'public/', $detailItem->lokasi)); // Hapus file dari storage
        }

        $detailItem->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $detailItem = DetailItem::find($item['id']);
            $detailItem->no_urut = $item['no_urut'];
            $detailItem->save();
        }

        return response()->json(['success' => true]);
    }
}
