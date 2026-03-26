<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_ranges_id',
        'ip',
        'statut',
        'equipment_id',
        'user_id',
        'structure_id',
    ];

    public function range()
    {
        return $this->belongsTo(IpRange::class, 'ip_ranges_id');
    }

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
}
