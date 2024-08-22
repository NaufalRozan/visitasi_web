<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akreditasi extends Model
{
    protected $table = 'akreditasi';
    protected $fillable = ['prodi_id', 'akreditas'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function standars()
    {
        return $this->hasMany(Standar::class);
    }
}
