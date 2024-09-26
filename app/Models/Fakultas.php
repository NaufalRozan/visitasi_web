<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    public $timestamps = false;
    // Nama tabel di database
    protected $table = 'units';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = ['id', 'nama_unit'];

    // Mengatur relasi satu-ke-banyak dengan model Prodi
    public function sub_units()
    {
        return $this->hasMany(Prodi::class, 'unit_id');
    }
}
