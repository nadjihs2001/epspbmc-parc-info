<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'cle',
        'nb_installations',
        'expiration',
        'alert_days',
        'fournisseur_id',
        'structure_id',
    ];

    protected $casts = [
        'expiration' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'fournisseur_id');
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function installations()
    {
        return $this->hasMany(LicenseInstallation::class);
    }
}
