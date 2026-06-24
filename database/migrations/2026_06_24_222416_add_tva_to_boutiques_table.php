<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->decimal('tva', 5, 2)->default(0); // Taux de TVA en pourcentage (ex: 18.00)
            $table->text('mentions_facture')->nullable(); // Mentions légales à afficher sur les factures
        });
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn(['tva', 'mentions_facture']);
        });
    }
};