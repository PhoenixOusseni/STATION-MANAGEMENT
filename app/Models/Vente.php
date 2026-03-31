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
        'index_depart',
        'index_final',
        'quantite',
        'retour_cuve',
        'quantite_vendue',
        'prix_unitaire',
        'montant_total',
        'mode_paiement',
        'date_vente',
        'client',
        'observation',
    ];

    protected $casts = [
        'index_depart'    => 'decimal:2',
        'index_final'     => 'decimal:2',
        'quantite'        => 'decimal:2',
        'retour_cuve'     => 'decimal:2',
        'quantite_vendue' => 'decimal:2',
        'prix_unitaire'   => 'decimal:2',
        'montant_total'   => 'decimal:2',
        'date_vente'      => 'datetime',
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
