<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'ville',
        'responsable',
    ];

    /**
     * Relation: Une station a plusieurs utilisateurs
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation: Une station a plusieurs cuves
     */
    public function cuves()
    {
        return $this->hasMany(Cuve::class);
    }

    /**
     * Relation: Une station a plusieurs commandes
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * Relation: Une station a plusieurs ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
