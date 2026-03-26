<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'vlan_id',
        'cidr',
        'passerelle',
        'dns1',
        'dns2',
        'structure_id',
    ];

    public function vlan()
    {
        return $this->belongsTo(Vlan::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
