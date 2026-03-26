<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nom',
    ];
}
