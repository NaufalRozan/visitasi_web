<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Prodi;
use App\Models\Fakultas;

class SyncProdiFakultas extends Command
{
    protected $signature = 'sync:prodi-fakultas';
    protected $description = 'Sync fakultas and prodi data from API to local database';

    public function handle()
    {
        // Ganti URL_API dengan endpoint API yang tepat
        $fakultasResponse = Http::get('http://192.168.100.129:8000/api/fakultas/');
        $prodiResponse = Http::get('http://192.168.100.129:8000/api/prodi/');

        $fakultasData = $fakultasResponse->json();
        $prodiData = $prodiResponse->json();

        // Sinkronisasi data fakultas
        foreach ($fakultasData as $fakultas) {
            Fakultas::updateOrCreate(
                ['id' => $fakultas['id']],
                ['nama_fakultas' => $fakultas['nama_fakultas']]
            );
        }

        // Sinkronisasi data prodi
        foreach ($prodiData as $prodi) {
            Prodi::updateOrCreate(
                ['id' => $prodi['id']],
                [
                    'nama_prodi' => $prodi['nama_prodi'],
                    'fakultas_id' => $prodi['fakultas_id']
                ]
            );
        }

        $this->info('Prodi and Fakultas data has been synced successfully.');
    }
}
