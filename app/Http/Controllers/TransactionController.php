<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['solde' => 0]);
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.dashboard', compact('user', 'wallet', 'transactions'));
    }

    public function depot(Request $request)
    {
        $request->validate(['montant' => 'required|numeric|min:1']);
        $user = Auth::user();
        $montant = (float)$request->montant;

        DB::transaction(function () use ($user, $montant) {
            $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['solde' => 0]);
            $wallet->solde += $montant;
            $wallet->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'depot',
                'montant' => $montant,
                'reference' => Str::upper(Str::random(10)),
                'statut' => 'effectuee'
            ]);
        });

        return back()->with('success', 'Dépôt effectué avec succès.');
    }

    public function retrait(Request $request)
    {
        $request->validate(['montant' => 'required|numeric|min:1']);
        $user = Auth::user();
        $montant = (float)$request->montant;

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['solde' => 0]);
        if ($wallet->solde < $montant) {
            return back()->withErrors(['montant' => 'Solde insuffisant.']);
        }

        DB::transaction(function () use ($user, $montant, $wallet) {
            $wallet->solde -= $montant;
            $wallet->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'retrait',
                'montant' => $montant,
                'reference' => Str::upper(Str::random(10)),
                'statut' => 'effectuee'
            ]);
        });

        return back()->with('success', 'Retrait effectué avec succès.');
    }

    public function transfert(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1',
            'telephone_dest' => 'required|string'
        ]);

        $user = Auth::user();
        $montant = (float)$request->montant;
        $telephoneDest = $request->telephone_dest;

        // Vérifie si le destinataire existe déjà
        $dest = User::where('telephone', $telephoneDest)->first();

        // ✅ Si le destinataire n’existe pas, on le crée automatiquement
        if (!$dest) {
            $dest = User::create([
                'name' => 'Utilisateur_' . Str::random(5),
                'email' => 'auto_' . Str::random(8) . '@example.com',
                'telephone' => $telephoneDest,
                'password' => bcrypt(Str::random(10))
            ]);
        }

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['solde' => 0]);
        if ($wallet->solde < $montant) {
            return back()->withErrors(['montant' => 'Solde insuffisant.']);
        }

        DB::transaction(function () use ($user, $dest, $montant) {
            $walletFrom = Wallet::firstOrCreate(['user_id' => $user->id], ['solde' => 0]);
            $walletTo = Wallet::firstOrCreate(['user_id' => $dest->id], ['solde' => 0]);

            $walletFrom->solde -= $montant;
            $walletFrom->save();

            $walletTo->solde += $montant;
            $walletTo->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'transfert',
                'montant' => $montant,
                'destinataire_id' => $dest->id,
                'reference' => Str::upper(Str::random(10)),
                'statut' => 'effectuee'
            ]);
        });

        return back()->with('success', 'Transfert effectué avec succès.');
    }
}
