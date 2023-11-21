<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = ['outlet_id', 'user_id', 'kode_transaksi', 'diskon', 'status'];
    protected $appends = ['total_price'];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaksiBarangs()
    {
        return $this->hasMany(TransaksiBarang::class, 'transaksi_id');
    }

    public function getTotalPriceNumberAttribute()
    {
        $total = 0;
        foreach ($this->transaksiBarangs as $key => $transaksi_barang) {
            $total += $transaksi_barang->barang->price;
        }


        if ($this->diskon) {
            $diskon = ($total * $this->diskon)/100;
            $total = $total - $diskon;
        }

        return $total;
    }

    public function getTotalPriceAttribute()
    {
        $total = $this->totalPriceNumber;
        return 'Rp. ' . number_format($total, 0, ',', '.');
    }


}
