<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'tag',
        'structure_id',
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
