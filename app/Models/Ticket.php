<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'equipment_id',
        'structure_id',
        'user_id',
        'type',
        'intervenant_id',
        'priorite',
        'statut',
        'sla_minutes',
        'description',
        'diagnostic',
        'solution',
        'cout_total',
        'ouvert_le',
        'cloture_le',
    ];

    protected $casts = [
        'ouvert_le' => 'datetime',
        'cloture_le' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class, 'intervenant_id');
    }

    public function parts()
    {
        return $this->hasMany(TicketPart::class);
    }

    public function reports()
    {
        return $this->hasMany(TicketReport::class);
    }
}
