<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaCandidateVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'acta_id',
        'candidate_id',
        'votes'
    ];

    /**
     * Relación con el acta
     */
    public function acta()
    {
        return $this->belongsTo(Acta::class);
    }

    /**
     * Relación con el candidato
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
