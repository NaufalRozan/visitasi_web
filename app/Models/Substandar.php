<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substandar extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'substandar';
    protected $fillable = ['no_urut', 'nama_substandar', 'standar_id'];

    public function standar()
    {
        return $this->belongsTo(Standar::class);
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }
}
