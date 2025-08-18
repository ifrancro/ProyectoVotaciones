<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Verificar si el usuario está activo
            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Tu cuenta está desactivada.',
                ]);
            }

            $request->session()->regenerate();
            
            // Redirigir según el rol
            if ($user->isAdmin()) {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
