<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('boutiques', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique(); // Nom unique de la boutique (affiché dans la sidebar)
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('logo')->nullable(); // Chemin vers le fichier logo (sinon initiale affichée)
            $table->string('devise')->default('FCFA');
            $table->enum('statut', ['active', 'suspendue', 'supprimee'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boutiques');
    }
};