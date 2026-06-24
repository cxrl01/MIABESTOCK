<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'numero_facture',
        'montant',
        'mode',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
        ];
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}