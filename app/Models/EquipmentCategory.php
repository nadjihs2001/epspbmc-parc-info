<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [];

    public array $translatedAttributes = [
        'nom',
    ];
}
