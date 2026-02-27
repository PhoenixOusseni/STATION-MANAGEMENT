<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'pistolet_id',
        'pompiste_id',
        'session_vente_id',
        'numero_vente',
        'numero_ticket',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'mode_paiement',
        'date_vente',
        'client',
        'observation',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_vente' => 'datetime',
    ];

    /**
     * Relation: Une vente appartient à une station
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Relation: Une vente est effectuée via un pistolet
     */
    public function pistolet()
    {
        return $this->belongsTo(Pistolet::class);
    }

    /**
     * Relation: Une vente est effectuée par un pompiste
     */
    public function pompiste()
    {
        return $this->belongsTo(User::class, 'pompiste_id');
    }

    /**
     * Relation: Une vente appartient à une session de vente
     */
    public function sessionVente()
    {
        return $this->belongsTo(SessionVente::class);
    }
}
