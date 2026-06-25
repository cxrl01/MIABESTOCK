<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    use HasFactory;

    protected $table = 'journal_activites';

    protected $fillable = [
        'user_id',
        'boutique_id',
        'action',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    /**
     * Helper pour enregistrer rapidement une activité.
     */
    public static function log(string $action, string $description, ?int $boutiqueId = null): void
    {
        static::create([
            'user_id' => auth()->id(),
            'boutique_id' => $boutiqueId,
            'action' => $action,
            'description' => $description,
        ]);
    }
}