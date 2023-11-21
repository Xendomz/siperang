<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('page.supplier.index', ['type_menu' => 'Supplier']);
    }

    public function getData()
    {
        if (request()->ajax()) {
            return response()->json(Supplier::where('outlet_id', auth()->user()->outlet->id)->latest()->get());
        }
        abort(404);
    }

    public function store(Request $request)
    {
        if (request()->ajax()) {
            Supplier::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'outlet_id' => auth()->user()->outlet->id
            ]);

            return response()->json(['message' => 'Add supplier successfully!']);
        }
        abort(404);
    }

    public function show(Supplier $supplier)
    {
        if (request()->ajax()) {
            return response()->json($supplier);
        }
        abort(404);
    }

    public function update(Request $request, Supplier $supplier)
    {
        if (request()->ajax()) {
            $supplier->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'outlet_id' => auth()->user()->outlet->id
            ]);

            return response()->json(['message' => 'Update supplier successfully!']);
        }
        abort(404);
    }

    public function delete(Supplier $supplier)
    {
        if (request()->ajax()) {
            $supplier->delete();

            return response()->json(['message' => 'Delete supplier successfully!']);
        }
        abort(404);
    }
}
