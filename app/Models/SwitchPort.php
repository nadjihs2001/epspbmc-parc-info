<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchPort extends Model
{
    use HasFactory;

    protected $fillable = [
        'switch_id',
        'port',
        'equipment_id',
        'vlan_id',
    ];

    public function switchDevice()
    {
        return $this->belongsTo(SwitchDevice::class, 'switch_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function vlan()
    {
        return $this->belongsTo(Vlan::class);
    }
}
