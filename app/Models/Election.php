<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'election_date',
        'is_active'
    ];

    protected $casts = [
        'election_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Relación con candidatos
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * Relación con actas
     */
    public function actas()
    {
        return $this->hasMany(Acta::class);
    }

    /**
     * Obtener candidatos activos ordenados por posición
     */
    public function activeCandidates()
    {
        return $this->candidates()->where('is_active', true)->orderBy('position');
    }

    /**
     * Obtener el total de votos de la elección
     */
    public function getTotalVotesAttribute()
    {
        return $this->actas()->sum('total_votes');
    }
}
