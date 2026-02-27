<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carburant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'prix_unitaire',
        'description',
    ];

    /**
     * Relation: Un carburant peut être dans plusieurs cuves
     */
    public function cuves()
    {
        return $this->hasMany(Cuve::class);
    }

    /**
     * Relation: Un carburant peut être dans plusieurs commandes
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
