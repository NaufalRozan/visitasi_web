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
        $prodis = Prodi::orderBy('fakultas_id')->get();

        return view('home', compact('prodis'));
    }

    /**
     * Show the login form for the selected prodi.
     */
    public function showLoginForm(Prodi $prodi)
    {
        return view('auth.prodi-login', compact('prodi'));
    }

    /**
     * Handle login request.
     */
    public function login(Request $request, Prodi $prodi)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Add prodi_id to the credentials
        $credentials['prodi_id'] = $prodi->id;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or prodi is incorrect.',
        ]);
    }
}
