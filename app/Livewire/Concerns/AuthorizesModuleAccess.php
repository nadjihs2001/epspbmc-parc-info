<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait AuthorizesModuleAccess
{
    protected function authorizePermission(string $permission): void
    {
        abort_unless(Auth::check(), 401);
        abort_unless(Auth::user()->can($permission), 403);
    }

    protected function authorizeRecordAccess(?Model $record, string $permission): void
    {
        $this->authorizePermission($permission);

        if (!$record) {
            return;
        }

        $user = Auth::user();

        if ($user?->hasRole('Super Admin')) {
            return;
        }

        $recordStructureId = data_get($record, 'structure_id');

        if (
            $recordStructureId !== null &&
            $user?->structure_id !== null &&
            (int) $recordStructureId !== (int) $user->structure_id
        ) {
            abort(403);
        }
    }

    protected function scopedStructureId(): ?int
    {
        $user = Auth::user();

        if (!$user || $user->hasRole('Super Admin')) {
            return null;
        }

        return $user->structure_id;
    }

    protected function lockStructureToAuthenticatedUser(array $form): array
    {
        $structureId = $this->scopedStructureId();

        if ($structureId !== null) {
            $form['structure_id'] = $structureId;
        }

        return $form;
    }
}
