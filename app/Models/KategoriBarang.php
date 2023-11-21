<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'outlet_id'];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }
}
