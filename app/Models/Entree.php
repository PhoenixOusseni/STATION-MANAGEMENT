<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    use HasFactory;

    protected $fillable = [
        'cuve_id',
        'commande_id',
        'jaugeage_id',
        'user_id',
        'numero_entree',
        'quantite_jaugee_avant',
        'observation_jaugeage',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'date_entree',
        'numero_bon_livraison',
        'observation',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_entree' => 'datetime',
    ];

    /**
     * Relation: Une entrée est liée à une cuve
     */
    public function cuve()
    {
        return $this->belongsTo(Cuve::class);
    }

    /**
     * Relation: Une entrée peut être liée à une commande
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Relation: Une entrée est enregistrée par un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation: Jaugeage effectué avant le dépotage
     */
    public function jaugeage()
    {
        return $this->belongsTo(Jaugeage::class);
    }
}
