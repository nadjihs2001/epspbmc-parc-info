<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'specialite',
        'structure_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'intervenant_id');
    }
}
