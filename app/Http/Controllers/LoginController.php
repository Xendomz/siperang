<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use HelperTrait;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withError('Wrong email or password');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $exitst_outlet = Outlet::where('name', 'LIKE', "%{$request->outlet_name}%")->first();
        if (!$exitst_outlet) {
            $outlet = Outlet::create([
                'name' => $request->outlet_name,
                'code' => $this->generateOutletCode()
            ]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'outlet_id' => $exitst_outlet ? $exitst_outlet->id : $outlet->id,
            'is_active' => !$exitst_outlet ? 1 : 0,
            'role' => $exitst_outlet ? 'Staff' : 'Owner'
        ]);

        if (!$exitst_outlet) {
            return redirect('/login')->withSuccess('Create account success. Please login to your account');
        }
        return redirect('/login')->withSuccess('Create account success. Please wait until owner approve');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
