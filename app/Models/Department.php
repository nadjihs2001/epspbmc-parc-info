<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'type',
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

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
