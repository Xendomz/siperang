<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Outlet;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = auth()->user()->isHasOutlet ? $this->userDashboard() : $this->adminDashboard();
        return view('page.dashboard', ['type_menu' => 'dashboard', 'data' => $data]);
    }

    public function userDashboard()
    {
        $data = [
            'totalSupplier' => Supplier::where('outlet_id', auth()->user()->outlet->id)->count(),
            'totalKategori' => KategoriBarang::where('outlet_id', auth()->user()->outlet->id)->count(),
            'totalUser' => User::where('outlet_id', auth()->user()->outlet->id)->where('role', '!=', 'Super Admin')->count(),
            'totalBarang' => Barang::whereHas('kategori', function ($q) {
                $q->where('outlet_id', auth()->user()->outlet->id);
            })->count(),
            'transactionData' => $this->chartTransaction()
        ];

        return $data;
    }

    public function chartTransaction()
    {
        $totalIncome = [];
        for ($i = 0; $i < 12; $i++) {
            $transaction = Transaksi::where('outlet_id', auth()->user()->outlet->id)->where('status', 'Sudah Bayar')->whereMonth('created_at', $i + 1)->whereYear('created_at', now()->format('Y'))->get();
            if ($transaction->isEmpty()) {
                $totalIncome[$i] = 0;
            } else {
                $tempImcome = 0;
                foreach ($transaction as $key => $transaction) {
                    $tempImcome += $transaction->total_price_number;
                }
                $totalIncome[$i] = $tempImcome;
            }
        }

        $label = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

        return [$label, $totalIncome];
    }

    public function adminDashboard()
    {
        $data = [
            'totalOutlet' => Outlet::count(),
            'totalUser' => User::count(),
        ];

        return $data;
    }
}
