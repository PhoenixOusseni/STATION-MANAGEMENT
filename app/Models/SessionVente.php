<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionVente extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_session',
        'station_id',
        'user_id',
        'statut',
        'date_debut',
        'date_fin',
        'observation',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    /* ──────────────────────── Relations ──────────────────────── */

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function jaugeages()
    {
        return $this->hasMany(Jaugeage::class);
    }

    public function jaugeagesDebut()
    {
        return $this->hasMany(Jaugeage::class)->where('type', 'debut_session');
    }

    public function jaugeagesFin()
    {
        return $this->hasMany(Jaugeage::class)->where('type', 'fin_session');
    }

    public function indexPompes()
    {
        return $this->hasMany(IndexPompe::class);
    }

    /* ──────────────────────── Helpers ──────────────────────── */

    public function isEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    public function isCloturee(): bool
    {
        return $this->statut === 'cloturee';
    }

    public function montantTotal(): float
    {
        return (float) $this->ventes()->sum('montant_total');
    }

    public function quantiteTotaleVendue(): float
    {
        return (float) $this->ventes()->sum('quantite');
    }
}
