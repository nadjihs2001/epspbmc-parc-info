<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'path_pdf',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
