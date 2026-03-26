<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'nom_piece',
        'quantite',
        'cout',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
