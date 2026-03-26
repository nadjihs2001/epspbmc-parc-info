<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Policies\EquipmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Equipment::class => EquipmentPolicy::class,
    ];
}
