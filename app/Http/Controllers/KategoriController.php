<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return view('page.kategori.index', ['type_menu' => 'Kategori']);
    }

    public function getData()
    {
        if (request()->ajax()) {
            return response()->json(KategoriBarang::where('outlet_id', auth()->user()->outlet->id)->latest()->get());
        }
        abort(404);
    }

    public function store(Request $request)
    {
        if (request()->ajax()) {
            KategoriBarang::create([
                'name' => $request->name,
                'outlet_id' => auth()->user()->outlet->id
            ]);

            return response()->json(['message' => 'Add Kategori successfully!']);
        }
        abort(404);
    }

    public function show(KategoriBarang $kategori)
    {
        if (request()->ajax()) {
            return response()->json($kategori);
        }
        abort(404);
    }

    public function update(Request $request, KategoriBarang $kategori)
    {
        if (request()->ajax()) {
            $kategori->update([
                'name' => $request->name,
                'outlet_id' => auth()->user()->outlet->id
            ]);

            return response()->json(['message' => 'Update Kategori successfully!']);
        }
        abort(404);
    }

    public function delete(KategoriBarang $kategori)
    {
        if (request()->ajax()) {
            $kategori->delete();

            return response()->json(['message' => 'Delete Kategori successfully!']);
        }
        abort(404);
    }
}
