<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'user_id',
        'mesa_number',
        'total_votes',
        'null_votes',
        'blank_votes',
        'observations',
        'is_validated',
        'photo_path'
    ];

    protected $casts = [
        'is_validated' => 'boolean'
    ];

    /**
     * Relación con la elección
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Relación con el usuario que registró el acta
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los votos de candidatos
     */
    public function candidateVotes()
    {
        return $this->hasMany(ActaCandidateVote::class);
    }

    /**
     * Obtener el total de votos válidos (sin nulos ni blancos)
     */
    public function getValidVotesAttribute()
    {
        return $this->total_votes - $this->null_votes - $this->blank_votes;
    }

    /**
     * Verificar si el acta está completo (total = 240)
     */
    public function isComplete()
    {
        return $this->total_votes === 240;
    }

    /**
     * Obtener la URL de la foto del acta
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        return null;
    }
}
