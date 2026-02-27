<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndexPompe extends Model
{
    use HasFactory;

    protected $fillable = [
        'pompe_id',
        'session_vente_id',
        'user_id',
        'index_depart',
        'index_final',
        'quantite_vendue_compteur',
        'date_releve_depart',
        'date_releve_final',
        'observation',
    ];

    protected $casts = [
        'index_depart'             => 'decimal:2',
        'index_final'              => 'decimal:2',
        'quantite_vendue_compteur' => 'decimal:2',
        'date_releve_depart'       => 'datetime',
        'date_releve_final'        => 'datetime',
    ];

    /* ──────────────────────── Relations ──────────────────────── */

    public function pompe()
    {
        return $this->belongsTo(Pompe::class);
    }

    public function sessionVente()
    {
        return $this->belongsTo(SessionVente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ──────────────────────── Helpers ──────────────────────── */

    public function isCloture(): bool
    {
        return $this->index_final !== null;
    }

    public function calculerQuantiteVendue(): float
    {
        if ($this->index_final !== null) {
            return (float) ($this->index_final - $this->index_depart);
        }
        return 0.0;
    }
}
