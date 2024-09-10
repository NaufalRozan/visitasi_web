<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akreditasi extends Model
{
    public $timestamps = false;
    protected $table = 'akreditasi';
    protected $fillable = [
        'prodi_id',
        'nama_akreditasi',
        'status',
    ];

    public function setActive()
    {
        // Nonaktifkan semua akreditasi lain untuk prodi yang sama
        Akreditasi::where('prodi_id', $this->prodi_id)
            ->update(['status' => 'tidak aktif']);

        // Aktifkan akreditasi ini
        $this->status = 'aktif';
        $this->save();
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function standars()
    {
        return $this->hasMany(Standar::class);
    }
}
