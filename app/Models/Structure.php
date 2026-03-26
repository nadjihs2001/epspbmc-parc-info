<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'nom',
        'type',
        'code',
        'adresse',
        'responsable_nom',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
