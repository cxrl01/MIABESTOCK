<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained('boutiques')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Qui a créé la commande
            $table->string('numero')->unique(); // Numéro unique auto-généré
            $table->enum('type', ['vente', 'livraison'])->default('vente');
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->enum('statut', ['en_cours', 'soldee', 'en_retard', 'annulee'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};