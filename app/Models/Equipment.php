<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory, Translatable;

    protected $table = 'equipments';

    protected $fillable = [
        'code_inventaire',
        'type_id',
        'category_id',
        'marque',
        'modele',
        'numero_serie',
        'mac',
        'ip_id',
        'os',
        'statut',
        'date_acquisition',
        'garantie_fin',
        'fournisseur_id',
        'structure_id',
        'location_id',
        'salle',
        'current_location',
        'user_id',
        'notes',
    ];

    public array $translatedAttributes = [
        'nom',
        'description',
        'localisation',
    ];

    protected $casts = [
        'date_acquisition' => 'date',
        'garantie_fin' => 'date',
    ];

    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'fournisseur_id');
    }

    public function photos()
    {
        return $this->hasMany(EquipmentPhoto::class);
    }

    public function ipAddress()
    {
        return $this->belongsTo(IpAddress::class, 'ip_id');
    }

    public function locationHistories()
    {
        return $this->hasMany(EquipmentLocationHistory::class)->orderByDesc('changed_at');
    }

    public function maintenancePlans()
    {
        return $this->hasMany(MaintenancePlan::class);
    }
}
