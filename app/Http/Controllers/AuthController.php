<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function showRegister(){ return view('auth.register'); }

    public function register(Request $request){
        $request->validate([
            'nom'=>'required|string',
            'prenom'=>'required|string',
            'telephone'=>'required|string|unique:users,telephone',
            'password'=>'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'telephone'=>$request->telephone,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'client'
        ]);

        Wallet::create(['user_id'=>$user->id,'solde'=>0]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function login(Request $request){
        $request->validate([
            'telephone'=>'required',
            'password'=>'required'
        ]);

        $credentials = $request->only('telephone','password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['telephone'=>'Identifiants incorrects']);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
