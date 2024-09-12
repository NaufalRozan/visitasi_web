<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'detail_item';

    protected $fillable = [
        'detail_id',
        'no_urut',
        'deskripsi',
        'lokasi',
        'tipe',
    ];

    public function detail()
    {
        return $this->belongsTo(Detail::class, 'detail_id');
    }
}
