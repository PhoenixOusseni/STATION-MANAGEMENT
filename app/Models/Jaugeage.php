<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jaugeage extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_jaugeage',
        'cuve_id',
        'user_id',
        'session_vente_id',
        'entree_id',
        'type',
        'quantite_mesuree',
        'quantite_theorique',
        'ecart',
        'date_jaugeage',
        'observation',
    ];

    protected $casts = [
        'quantite_mesuree'   => 'decimal:2',
        'quantite_theorique' => 'decimal:2',
        'ecart'              => 'decimal:2',
        'date_jaugeage'      => 'datetime',
    ];

    /* ──────────────────────── Relations ──────────────────────── */

    public function cuve()
    {
        return $this->belongsTo(Cuve::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sessionVente()
    {
        return $this->belongsTo(SessionVente::class);
    }

    public function entree()
    {
        return $this->belongsTo(Entree::class);
    }

    /* ──────────────────────── Helpers ──────────────────────── */

    public function libellType(): string
    {
        return match($this->type) {
            'debut_session'   => 'Jauge Début Session',
            'fin_session'     => 'Jauge Fin Session',
            'avant_depotage'  => 'Jauge Avant Dépotage',
            default           => ucfirst($this->type),
        };
    }

    public function badgeClass(): string
    {
        return match($this->type) {
            'debut_session'   => 'bg-primary',
            'fin_session'     => 'bg-success',
            'avant_depotage'  => 'bg-warning',
            default           => 'bg-secondary',
        };
    }

    public function ecartBadgeClass(): string
    {
        if ($this->ecart > 0)  return 'bg-success';
        if ($this->ecart < 0)  return 'bg-danger';
        return 'bg-secondary';
    }
}
