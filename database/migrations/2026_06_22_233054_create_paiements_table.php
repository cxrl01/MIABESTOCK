<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->string('numero_facture')->unique(); // Ex: CMD-20260622-00001
            $table->decimal('montant', 12, 2);
            $table->enum('mode', ['especes', 'mobile_money', 'cheque', 'virement'])->default('especes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};