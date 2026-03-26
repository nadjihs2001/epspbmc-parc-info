<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'path',
        'caption',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
