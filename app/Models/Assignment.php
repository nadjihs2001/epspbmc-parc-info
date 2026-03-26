<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'user_id',
        'structure_id',
        'location_id',
        'date_debut',
        'date_fin',
        'justification',
        'signature_path',
        'actif',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
