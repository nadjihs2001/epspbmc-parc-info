<?php

namespace App\Repositories;

use App\Models\Equipment;

class EquipmentRepository extends BaseRepository
{
    public function __construct(Equipment $model)
    {
        parent::__construct($model);
    }
}
