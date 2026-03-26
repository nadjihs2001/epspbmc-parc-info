<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'score_risque',
        'recommendation',
        'predicted_failure_date',
    ];

    protected $casts = [
        'predicted_failure_date' => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
