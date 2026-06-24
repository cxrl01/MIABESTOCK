<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained('boutiques')->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->string('code')->unique(); // Référence unique du produit
            $table->string('nom');
            $table->decimal('prix_achat', 12, 2);
            $table->decimal('prix_vente', 12, 2);
            $table->integer('quantite_stock')->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};