<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'categorie_id',
        'code',
        'nom',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'seuil_alerte',
    ];

    protected function casts(): array
    {
        return [
            'prix_achat' => 'decimal:2',
            'prix_vente' => 'decimal:2',
        ];
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // Helper : stock critique ?
    public function estEnStockCritique(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte;
    }
}