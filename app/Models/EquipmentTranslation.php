<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'description',
        'localisation',
    ];
}
