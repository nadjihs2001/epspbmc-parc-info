<?php

namespace App\Services\Inventory;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class InventoryReportService
{
    public function query(array $filters = [], ?User $user = null): Builder
    {
        $query = Equipment::query()
            ->with(['type', 'structure', 'location.structure', 'location.department', 'location.parent']);

        $scopedStructureId = $user && !$user->hasRole('Super Admin') ? $user->structure_id : null;
        $selectedStructureId = $scopedStructureId ?? ($filters['structure_id'] ?? null);

        $query->when($selectedStructureId, fn ($builder) => $builder->where('structure_id', $selectedStructureId));
        $query->when(!empty($filters['statut']), fn ($builder) => $builder->where('statut', $filters['statut']));
        $query->when(!empty($filters['type_id']), fn ($builder) => $builder->where('type_id', $filters['type_id']));
        $query->when(!empty($filters['search']), function ($builder) use ($filters) {
            $search = '%'.$filters['search'].'%';

            $builder->where(function ($nested) use ($search) {
                $nested->where('code_inventaire', 'like', $search)
                    ->orWhere('marque', 'like', $search)
                    ->orWhere('modele', 'like', $search)
                    ->orWhere('numero_serie', 'like', $search)
                    ->orWhereHas('translations', fn ($translationQuery) => $translationQuery->where('nom', 'like', $search))
                    ->orWhereHas('location', fn ($locationQuery) => $locationQuery->where('nom', 'like', $search))
                    ->orWhereHas('structure', fn ($structureQuery) => $structureQuery->where('nom', 'like', $search));
            });
        });

        return $query->latest();
    }

    public function summary(array $filters = [], ?User $user = null): array
    {
        $query = $this->query($filters, $user);

        return [
            'total' => (clone $query)->count(),
            'actif' => (clone $query)->where('statut', 'actif')->count(),
            'panne' => (clone $query)->where('statut', 'panne')->count(),
            'maintenance' => (clone $query)->where('statut', 'maintenance')->count(),
            'reforme' => (clone $query)->where('statut', 'reforme')->count(),
        ];
    }
}
