<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'boutique_id',
        'est_actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'est_actif' => 'boolean',
        ];
    }

    // Relations
    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    // Helpers de rôle
    public function estGerant(): bool
    {
        return $this->role === 'gerant';
    }

    public function estGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    public function estCommercial(): bool
    {
        return $this->role === 'commercial';
    }

    public function estSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}