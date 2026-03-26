<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('equipments.view');
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return $user->can('equipments.view') && $user->structure_id === $equipment->structure_id;
    }

    public function create(User $user): bool
    {
        return $user->can('equipments.create');
    }

    public function update(User $user, Equipment $equipment): bool
    {
        return $user->can('equipments.update') && $user->structure_id === $equipment->structure_id;
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        return $user->can('equipments.delete') && $user->structure_id === $equipment->structure_id;
    }
}
