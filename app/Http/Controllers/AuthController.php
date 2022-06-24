<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Search user by email
        $user = User::where('email', $request->email)->first();

        // If user not found
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email e/ou senha inválidos.'
            ], 404);
        }

        // User with role "customer" can't login
        if ($user->role === 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não tem permissão para acessar o painel.'
            ], 403);
        }

        // Try to login
        if (Auth::attempt($credentials)) {
			// Add log
			add_log('User [' . $user->id . '] ' . $user->name . ' logged in.');

			return response()->json([
                'success' => true,
                'message' => __('Logado com sucesso!'),
                'redirect' => url('panel/dashboard')
            ], 200);
        }

        // Login failed
        return response()->json([
            'success' => false,
            'message' => __('Email e/ou senha inválidos!')
        ], 404);
    }

    public function index()
    {
        return view('auth.index');
    }

    public function logout()
    {
		// Add log
		add_log('User [' . Auth::user()->id . '] ' . Auth::user()->name . ' logged out.');

		Auth::logout();
        return redirect('/');
    }
}
