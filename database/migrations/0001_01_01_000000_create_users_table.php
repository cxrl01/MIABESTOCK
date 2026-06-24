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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom complet de l'utilisateur
            $table->string('email')->unique(); // Email unique pour la connexion
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Mot de passe (hashé)
            $table->enum('role', ['super_admin', 'gerant', 'gestionnaire', 'commercial'])
                  ->default('gerant'); // Rôle par défaut : Gérant (créateur de la boutique)
            $table->foreignId('boutique_id')->nullable(); // Boutique à laquelle l'utilisateur appartient (null pour le Super Admin)
            $table->boolean('est_actif')->default(true); // Compte actif ou suspendu
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};