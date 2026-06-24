<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvement_stocks';

    protected $fillable = [
        'produit_id',
        'type',
        'quantite',
        'motif',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}