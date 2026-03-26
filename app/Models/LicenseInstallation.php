<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseInstallation extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_license_id',
        'equipment_id',
        'installe_le',
        'actif',
    ];

    protected $casts = [
        'installe_le' => 'date',
        'actif' => 'boolean',
    ];

    public function license()
    {
        return $this->belongsTo(SoftwareLicense::class, 'software_license_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
