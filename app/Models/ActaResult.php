<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'acta_id',
        'candidate_id',
        'votos'
    ];

    // Relaciones
    public function acta()
    {
        return $this->belongsTo(Acta::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
