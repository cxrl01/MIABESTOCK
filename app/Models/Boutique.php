<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'logo',
        'devise',
        'statut',
        'tva',
        'mentions_facture',
    ];

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Categorie::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function fournisseurs()
    {
        return $this->hasMany(Fournisseur::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}