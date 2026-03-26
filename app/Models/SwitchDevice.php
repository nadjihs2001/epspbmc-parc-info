<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchDevice extends Model
{
    use HasFactory;

    protected $table = 'switches';

    protected $fillable = [
        'nom',
        'modele',
        'ip_gestion',
        'structure_id',
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function ports()
    {
        return $this->hasMany(SwitchPort::class, 'switch_id');
    }
}
