<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pompe extends Model
{
    use HasFactory;

    protected $fillable = [
        'cuve_id',
        'nom',
        'numero_serie',
        'etat',
        'date_maintenance',
    ];

    protected $casts = [
        'date_maintenance' => 'date',
    ];

    /**
     * Relation: Une pompe est reliée à une cuve
     */
    public function cuve()
    {
        return $this->belongsTo(Cuve::class);
    }

    /**
     * Relation: Une pompe a plusieurs pistolets
     */
    public function pistolets()
    {
        return $this->hasMany(Pistolet::class);
    }

    /**
     * Relation: Une pompe a plusieurs relevés d'index
     */
    public function indexPompes()
    {
        return $this->hasMany(IndexPompe::class);
    }
}
