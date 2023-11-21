<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $fillable = ['barang_id', 'stock', 'tanggal_input', 'keterangan', 'user_id'];
    protected $dates = ['tanggal_input', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function scopeOutlet($query, $value)
    {
        return $query->whereHas('barang', function ($q) use ($value){
            $q->whereHas('kategori', function ($q) use ($value){
                $q->where('outlet_id', $value);
            });
        });
    }
}
