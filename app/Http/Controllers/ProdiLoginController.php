<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdiLoginController extends Controller
{
    /**
     * Display the home page with prodi selection.
     */
    public function index()
    {
        // Mengambil data prodi dan mengurutkannya berdasarkan id_fakultas
        $sub_units = Prodi::orderBy('unit_id')->get();

        return view('home', compact('sub_units'));
    }

    /**
     * Show the login form for the selected prodi.
     */
    public function showLoginForm(Prodi $sub_units)
    {
        return view('auth.prodi-login', compact('sub_units'));
    }

    /**
     * Handle login request.
     */
    public function login(Request $request, Prodi $sub_units)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Add prodi_id to the credentials
        $credentials['sub_unit_id'] = $sub_units->id;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or prodi is incorrect.',
        ]);
    }
}
