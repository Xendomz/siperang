<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    use HelperTrait;

    public function index()
    {
        return view('page.outlet.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            return response()->json(Outlet::latest()->get());
        }
        abort(404);
    }

    public function store(Request $request)
    {
        if (request()->ajax()) {
            Outlet::create([
                'name' => $request->name,
                'code' => $this->generateOutletCode(),
            ]);

            return response()->json(['message' => 'Add Outlet successfully!']);
        }
        abort(404);
    }

    public function show(Outlet $outlet)
    {
        if (request()->ajax()) {
            return response()->json($outlet);
        }
        abort(404);
    }

    public function update(Request $request, Outlet $outlet)
    {
        if (request()->ajax()) {
            $outlet->update([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Update Outlet successfully!']);
        }
        abort(404);
    }

    public function delete(Outlet $outlet)
    {
        if (request()->ajax()) {
            if (auth()->user()->isAdmin && auth()->user()->isHasOutlet) {
                auth()->user()->update(['outlet_id' => null]);
                $outlet->delete();

                return response()->json(['is_redirect' => true, 'redirect_path' => route('dashboard')]);
            }
            $outlet->delete();

            return response()->json(['message' => 'Delete Outlet successfully!']);
        }
        abort(404);
    }

    public function syncOutlet(Outlet $outlet)
    {
        auth()->user()->update(['outlet_id' => $outlet->id]);

        return redirect('/');
    }

    public function desyncOutlet()
    {
        auth()->user()->update(['outlet_id' => null]);

        return redirect('/');
    }
}
