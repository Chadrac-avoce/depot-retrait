<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['depot','retrait','transfert']);
            $table->decimal('montant', 15, 2);
            $table->string('numero_mobile')->nullable();
            $table->foreignId('destinataire_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('statut', ['en_attente','effectuee','annulee'])->default('effectuee');
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
