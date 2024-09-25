<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Prodi;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('home', ['sub_units' => Prodi::all()]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            // Simpan prodi_id ke dalam session
            session(['sub_unit_id' => $request->sub_unit_id]);

            $request->session()->regenerate();

            if ($request->user()->role === 'Universitas') {
                return redirect('/user');
            }

            return redirect()->intended(route('dashboard'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                    'sub_unit_id' => 'Sub Unit tidak sesuai dengan akun Anda.',
                ])
                ->withInput($request->only('email', 'sub_unit_id'));
        }
    }




    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
