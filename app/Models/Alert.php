<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'message',
        'entity_type',
        'entity_id',
        'priorite',
        'resolved_at',
        'structure_id',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
