<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenancePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'periodicite_jours',
        'prochain',
        'statut',
    ];

    protected $casts = [
        'prochain' => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
