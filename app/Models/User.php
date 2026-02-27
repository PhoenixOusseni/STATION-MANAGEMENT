<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'station_id',
        'nom',
        'prenom',
        'telephone',
        'login',
        'email',
        'password',
        'role',
        'statut',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation: Un utilisateur appartient à une station
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Relation: Un pompiste gère plusieurs pistolets
     */
    public function pistolets()
    {
        return $this->hasMany(Pistolet::class, 'pompiste_id');
    }

    /**
     * Relation: Un utilisateur effectue plusieurs ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'pompiste_id');
    }

    /**
     * Relation: Un utilisateur passe plusieurs commandes
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * Relation: Un utilisateur enregistre plusieurs entrées
     */
    public function entrees()
    {
        return $this->hasMany(Entree::class);
    }

    /**
     * Relation: Un utilisateur ouvre des sessions de vente
     */
    public function sessionVentes()
    {
        return $this->hasMany(SessionVente::class);
    }

    /**
     * Relation: Un utilisateur effectue des jaugeages
     */
    public function jaugeages()
    {
        return $this->hasMany(Jaugeage::class);
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est gestionnaire
     */
    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }

    /**
     * Vérifier si l'utilisateur est pompiste
     */
    public function isPompiste()
    {
        return $this->role === 'pompiste';
    }
}
