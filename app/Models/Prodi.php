<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    public $timestamps = false;
    // Nama tabel di database
    protected $table = 'sub_units';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = ['id', 'nama_sub_unit', 'unit_id'];

    // Mengatur relasi banyak-ke-satu dengan model Fakultas
    public function units()
    {
        return $this->belongsTo(Fakultas::class, 'unit_id');
    }

    public function akreditasis()
    {
        return $this->hasMany(Akreditasi::class, 'sub_unit_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_sub_unit', 'sub_unit_id', 'user_id');
    }
}
