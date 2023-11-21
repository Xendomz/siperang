<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Transaksi;
use App\Models\TransaksiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('page.transaksi.index', ['type_menu' => 'transaksi']);
    }

    public function getData()
    {
        if (request()->ajax()) {
            return response()->json(Transaksi::with('outlet', 'user')->where('outlet_id', auth()->user()->outlet->id)->latest()->get());
        }
        abort(404);
    }

    private function generateKodeBarang()
    {
        $kode_transaksi = Transaksi::where('outlet_id', auth()->user()->outlet->id)->max('kode_transaksi');

        $order = (int) substr($kode_transaksi, 3, 3);
        $order++;

        $kode_transaksi = auth()->user()->outlet->code. sprintf('%03s', $order);

        return $kode_transaksi;
    }

    public function create()
    {
        $kode_transaksi = $this->generateKodeBarang();
        $barangs = Barang::whereHas('kategori', function ($q){
            $q->where('outlet_id', auth()->user()->outlet->id);
        })->latest()->get();

        return view('page.transaksi.create', ['type_menu' => 'transaksi', 'kode_transaksi' => $kode_transaksi, 'barangs' => $barangs]);
    }

    private function sumQty($barangs)
    {
        $barangs = collect($barangs)->groupBy('barang');
        $result = [];
        $barangs->each(function ($group) use (&$result) {
            $barang = $group->first()['barang'];
            $qty = $group->sum('qty');

            $result[] = [
                'barang' => Barang::find($barang),
                'qty' => $qty,
            ];
        });

        return $result;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->has('is_draft')) {
                $draft_transaksi = Transaksi::where('kode_transaksi', $request->kode_transaksi)->first();
            }

            if (isset($draft_transaksi)) {
                $transaksi = $draft_transaksi->update([
                    'user_id' => auth()->user()->id,
                    'diskon' => $request->diskon ?? 0,
                    'status' => $request->is_draft ? 'Belum Bayar' : 'Sudah Bayar',
                ]);
            } else {
                $transaksi = Transaksi::create([
                    'outlet_id' => auth()->user()->outlet->id,
                    'user_id' => auth()->user()->id,
                    'kode_transaksi' => $request->kode_transaksi,
                    'diskon' => $request->diskon ?? 0,
                    'status' => $request->is_draft ? 'Belum Bayar' : 'Sudah Bayar',
                ]);
            }

            foreach ($this->sumQty($request->barangs) as $key => $filtered_Barangs) {
                if ($filtered_Barangs['barang']->stok < $filtered_Barangs['qty']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Terdapat stok barang yang tidak mencukupi')->withInput($request->input());
                }

                TransaksiBarang::create([
                    'barang_id' => $filtered_Barangs['barang']->id,
                    'transaksi_id' => $transaksi->id,
                    'qty' => $filtered_Barangs['qty']
                ]);

                if (!$request->is_draft) {
                    $filtered_Barangs['barang']->update([
                        'stok' => $filtered_Barangs['barang']->stok - $filtered_Barangs['qty']
                    ]);

                    BarangKeluar::create([
                        'user_id' => auth()->user()->id,
                        'barang_id' => $filtered_Barangs['barang']->id,
                        'stock' => $filtered_Barangs['qty'],
                        'keterangan' => 'Transaksi',
                        'tanggal_input' => now()
                    ]);
                }

            }
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', $request->is_draft ? 'Transaksi berhsil disimpan' : 'Berhasil melakukan transaksi');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('transaksi.index')->with('error', 'Terjadi kesalahan pada penginputan data'. $th->getMessage());
        }
    }

    public function update(Request $request, $transaksi)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::where('kode_transaksi', $transaksi)->first();

            if ($transaksi->status == 'Sudah Bayar') {
                abort(404);
            }

            $transaksi->update([
                'user_id' => auth()->user()->id,
                'diskon' => $request->diskon ?? 0,
                'status' => $request->is_draft ? 'Belum Bayar' : 'Sudah Bayar',
            ]);
            $transaksi->transaksiBarangs()->delete();

            foreach ($this->sumQty($request->barangs) as $key => $filtered_Barangs) {
                if ($filtered_Barangs['barang']->stok < $filtered_Barangs['qty']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Terdapat stok barang yang tidak mencukupi')->withInput($request->input());
                }

                TransaksiBarang::create([
                    'barang_id' => $filtered_Barangs['barang']->id,
                    'transaksi_id' => $transaksi->id,
                    'qty' => $filtered_Barangs['qty']
                ]);

                if (!$request->is_draft) {
                    $filtered_Barangs['barang']->update([
                        'stok' => $filtered_Barangs['barang']->stok - $filtered_Barangs['qty']
                    ]);

                    BarangKeluar::create([
                        'user_id' => auth()->user()->id,
                        'barang_id' => $filtered_Barangs['barang']->id,
                        'stock' => $filtered_Barangs['qty'],
                        'keterangan' => 'Transaksi',
                        'tanggal_input' => now()
                    ]);
                }

            }
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', $request->is_draft ? 'Transaksi berhsil disimpan' : 'Berhasil melakukan transaksi');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('transaksi.index')->with('error', 'Terjadi kesalahan pada penginputan data'. $th->getMessage());
        }
    }

    public function showDraft(Transaksi $transaksi)
    {
        if ($transaksi->status == 'Belum Bayar') {
            $barangs = Barang::whereHas('kategori', function ($q){
                $q->where('outlet_id', auth()->user()->outlet->id);
            })->latest()->get();
            return view('page.transaksi.edit', compact('transaksi', 'barangs'));
        }
        abort(404);
    }

    public function detail(Transaksi $transaksi){
        return view('page.transaksi.detail', compact('transaksi'));
    }

    public function showInvoice(Request $request)
    {
        $data = ['filtered_barangs' => $this->sumQty($request->barangs)];
        $data = array_merge($data, $request->all());
        return view('page.transaksi.invoice', $data);
    }

    public function delete(Transaksi $transaksi)
    {
        $transaksi->delete();

        return response()->json(['message' => 'Delete Transaksi successfully!']);
    }
}
