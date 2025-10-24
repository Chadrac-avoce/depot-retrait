<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Admin
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'telephone' => '60000000',
            'email' => 'admin@app.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        Wallet::create([
            'user_id' => $admin->id,
            'solde' => 0
        ]);

        // Client de test
        $client = User::create([
            'nom' => 'Client',
            'prenom' => 'Test',
            'telephone' => '60000001',
            'email' => 'client@app.com',
            'password' => Hash::make('client123'),
            'role' => 'client'
        ]);
        Wallet::create([
            'user_id' => $client->id,
            'solde' => 5000.00
        ]);
    }
}
