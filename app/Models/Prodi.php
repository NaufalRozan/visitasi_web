<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    public $timestamps = false;
    // Nama tabel di database
    protected $table = 'prodi';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = ['id', 'nama_prodi', 'fakultas_id'];

    // Mengatur relasi banyak-ke-satu dengan model Fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}
