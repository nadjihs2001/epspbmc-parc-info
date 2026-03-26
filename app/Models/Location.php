<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'structure_id',
        'department_id',
        'parent_id',
        'nom',
        'type',
        'code',
        'etage',
        'details',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    protected function fullLabel(): Attribute
    {
        return Attribute::get(function (): string {
            return collect([
                $this->structure?->nom,
                $this->department?->nom,
                $this->parent?->nom,
                $this->nom,
            ])->filter()->implode(' / ');
        });
    }
}
