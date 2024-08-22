<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standar extends Model
{
    public $timestamps = false;
    protected $table = 'standar';
    protected $fillable = ['no_urut', 'nama_standar', 'akreditasi_id'];

    public function akreditasi()
    {
        return $this->belongsTo(Akreditasi::class);
    }
}
