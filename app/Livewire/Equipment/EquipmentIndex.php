<?php

namespace App\Livewire\Equipment;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class EquipmentIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorizePermission('equipments.view');
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();
        $search = '%'.$this->search.'%';

        $equipments = Equipment::query()
            ->with(['type', 'location.structure', 'location.department', 'location.parent'])
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->when($this->status !== '', fn ($q) => $q->where('statut', $this->status))
            ->when($this->search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('code_inventaire', 'like', $search)
                        ->orWhere('marque', 'like', $search)
                        ->orWhere('modele', 'like', $search)
                        ->orWhere('current_location', 'like', $search)
                        ->orWhere('salle', 'like', $search)
                        ->orWhereHas('location', fn ($locationQuery) => $locationQuery->where('nom', 'like', $search))
                        ->orWhereHas('translations', fn ($t) => $t->where('nom', 'like', $search));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.equipment.index', [
            'equipments' => $equipments,
        ])->layout('layouts.app');
    }
}
