<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\KategoriBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $kategories = KategoriBarang::where('outlet_id', auth()->user()->outlet->id)->get();
        $suppliers = Supplier::where('outlet_id', auth()->user()->outlet->id)->get();
        return view('page.barang.index', ['type_menu' => 'Barang', 'kategories' => $kategories, 'suppliers' => $suppliers]);
    }

    public function getData()
    {
        if (request()->ajax()) {
            $barang = Barang::whereHas('kategori', function ($q){
                $q->where('outlet_id', auth()->user()->outlet->id);
            })->with('kategori', 'supplier')->get();

            return $barang;
        }
        abort(404);
    }

    public function store(Request $request)
    {
        if (request()->ajax()) {
            DB::beginTransaction();
            try {
                $barang = Barang::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'stok' => $request->stock,
                    'expired_date' => $request->expired_date,
                    'kategori_id' => $request->kategori,
                    'supplier_id' => $request->supplier
                ]);

                BarangMasuk::create([
                    'user_id' => auth()->user()->id,
                    'barang_id' => $barang->id,
                    'stock' => $request->stock,
                    'keterangan' => 'Tambah Barang',
                    'tanggal_input' => $request->tanggal_input
                ]);

                DB::commit();
                return response()->json(['message' => 'Add Kategori successfully!']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['message' => "Something went wrong. Please try again" . $th->getMessage()], 400);
            }
        }
        abort(404);
    }

    public function show(Barang $barang)
    {
        if (request()->ajax()) {
            return response()->json($barang);
        }
        abort(404);
    }

    public function update(Request $request, Barang $barang)
    {
        if (request()->ajax()) {
            DB::beginTransaction();
            try {
                if ($request->stock - $barang->stok > 0) {
                    BarangMasuk::create([
                        'user_id' => auth()->user()->id,
                        'barang_id' => $barang->id,
                        'stock' => $request->stock - $barang->stok,
                        'keterangan' => 'Edit Barang',
                        'tanggal_input' => $request->tanggal_input
                    ]);
                } else if ($request->stock - $barang->stok < 0) {
                    BarangKeluar::create([
                        'user_id' => auth()->user()->id,
                        'barang_id' => $barang->id,
                        'stock' => abs($request->stock - $barang->stok),
                        'keterangan' => 'Edit Barang',
                        'tanggal_input' => $request->tanggal_input
                    ]);
                }

                $barang->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'stok' => $request->stock,
                    'expired_date' => $request->expired_date,
                    'kategori_id' => $request->kategori,
                    'supplier_id' => $request->supplier
                ]);

                DB::commit();
                return response()->json(['message' => 'Update Barang successfully!']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['message' => "Something went wrong. Please try again" . $th->getMessage()], 400);
            }
        }
        abort(404);
    }

    public function delete(Barang $barang)
    {
        if (request()->ajax()) {
            $barang->delete();

            return response()->json(['message' => 'Delete Kategori successfully!']);
        }
    }

    public function historisBarang()
    {
        $barang_masuks = BarangMasuk::outlet(auth()->user()->outlet->id)->latest()->get();
        $barang_keluars = BarangKeluar::outlet(auth()->user()->outlet->id)->latest()->get();

        return view('page.barang.historisBarang', ['type_menu' => 'barang', 'barang_masuks' => $barang_masuks, 'barang_keluars' => $barang_keluars]);
    }
}
