<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('page.user.index', ['type_menu' => 'user']);
    }

    public function getData()
    {
        return response()->json(User::where('outlet_id', auth()->user()->outlet_id)->where('id', '!=', auth()->user()->id)->where('role', '!=', 'Super Admin')->latest()->get());
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => $user->is_active ? 0 : 1]);

        return response()->json(['message' => 'Change User Status successfully!']);
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'outlet_id' => auth()->user()->outlet->id,
            'is_active' => 0,
            'role' => 'Staff'
        ]);

        return response()->json(['message' => 'Add User successfully!']);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->has('password') ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json(['message' => 'Update User successfully!']);
    }

    public function delete(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Delete User successfully!']);
    }

    // for update authenticate user
    public function profile()
    {
        return view('page.user.profile');
    }

    public function changeProfile(Request $request)
    {
        auth()->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->has('password') ? bcrypt($request->password) : auth()->user()->password
        ]);

        return redirect()->route('user.profile')->with('success', 'Update profile successfully');
    }
}
