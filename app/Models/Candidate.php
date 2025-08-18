<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'name',
        'party',
        'color_hex',
        'position',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relación con la elección
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Relación con los votos en actas
     */
    public function actaVotes()
    {
        return $this->hasMany(ActaCandidateVote::class);
    }

    /**
     * Obtener el total de votos del candidato
     */
    public function getTotalVotesAttribute()
    {
        return $this->actaVotes()->sum('votes');
    }
}
