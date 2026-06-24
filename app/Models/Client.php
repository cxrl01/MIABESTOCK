<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'nom_complet',
        'telephone',
        'email',
        'adresse',
        'solde_dette',
    ];

    protected function casts(): array
    {
        return [
            'solde_dette' => 'decimal:2',
        ];
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}