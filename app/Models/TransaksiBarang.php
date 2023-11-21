<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBarang extends Model
{
    use HasFactory;
    protected $fillable = ['barang_id', 'transaksi_id', 'qty'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
