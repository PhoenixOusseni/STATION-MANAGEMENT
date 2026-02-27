<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuve extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'carburant_id',
        'nom',
        'capacite_max',
        'stock_actuel',
        'stock_min',
        'numero_serie',
    ];

    protected $casts = [
        'capacite_max' => 'decimal:2',
        'stock_actuel' => 'decimal:2',
        'stock_min' => 'decimal:2',
    ];

    /**
     * Relation: Une cuve appartient à une station
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Relation: Une cuve contient un type de carburant
     */
    public function carburant()
    {
        return $this->belongsTo(Carburant::class);
    }

    /**
     * Relation: Une cuve a plusieurs pompes
     */
    public function pompes()
    {
        return $this->hasMany(Pompe::class);
    }

    /**
     * Relation: Une cuve a plusieurs entrées
     */
    public function entrees()
    {
        return $this->hasMany(Entree::class);
    }

    /**
     * Relation: Une cuve a plusieurs jaugeages
     */
    public function jaugeages()
    {
        return $this->hasMany(Jaugeage::class);
    }

    /**
     * Vérifier si le stock est en alerte
     */
    public function isStockAlerte()
    {
        return $this->stock_actuel <= $this->stock_min;
    }

    /**
     * Calculer le pourcentage de remplissage
     */
    public function pourcentageRemplissage()
    {
        return ($this->stock_actuel / $this->capacite_max) * 100;
    }
}
