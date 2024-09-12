<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'detail';
    protected $fillable = ['no_urut', 'nama_detail', 'substandar_id'];

    public function substandar()
    {
        return $this->belongsTo(Substandar::class);
    }

    public function items()
    {
        return $this->hasMany(DetailItem::class, 'detail_id');
    }
}
