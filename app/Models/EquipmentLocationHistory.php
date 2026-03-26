<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentLocationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'user_id',
        'previous_location',
        'new_location',
        'note',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
