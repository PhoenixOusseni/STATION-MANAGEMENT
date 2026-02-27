<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pistolet extends Model
{
    use HasFactory;

    protected $fillable = [
        'pompe_id',
        'pompiste_id',
        'nom',
        'numero',
        'etat',
    ];

    /**
     * Relation: Un pistolet est relié à une pompe
     */
    public function pompe()
    {
        return $this->belongsTo(Pompe::class);
    }

    /**
     * Relation: Un pistolet est géré par un pompiste
     */
    public function pompiste()
    {
        return $this->belongsTo(User::class, 'pompiste_id');
    }

    /**
     * Relation: Un pistolet a plusieurs ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
