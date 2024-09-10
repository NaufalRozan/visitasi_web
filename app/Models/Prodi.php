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

    public function akreditasis()
    {
        return $this->hasMany(Akreditasi::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_prodi', 'prodi_id', 'user_id');
    }
}
