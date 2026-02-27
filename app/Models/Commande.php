<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'carburant_id',
        'user_id',
        'numero_commande',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'fournisseur',
        'statut',
        'date_commande',
        'date_livraison_prevue',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_commande' => 'date',
        'date_livraison_prevue' => 'date',
    ];

    /**
     * Relation: Une commande appartient à une station
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Relation: Une commande concerne un carburant
     */
    public function carburant()
    {
        return $this->belongsTo(Carburant::class);
    }

    /**
     * Relation: Une commande est passée par un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation: Une commande peut avoir une entrée (réception)
     */
    public function entree()
    {
        return $this->hasOne(Entree::class);
    }
}
