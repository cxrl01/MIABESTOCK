<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'client_id',
        'fournisseur_id',
        'user_id',
        'numero',
        'type',
        'total_ttc',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'total_ttc' => 'decimal:2',
        ];
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Helper : solde restant à payer
    public function soldeRestant()
    {
        return $this->total_ttc - $this->paiements->sum('montant');
    }
}