<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'payload',
        'response',
        'status',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
