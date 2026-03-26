<?php

namespace App\Livewire\Maintenance;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\MaintenancePlan;
use Livewire\Component;
use Livewire\WithPagination;

class MaintenancePlanIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $due = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'due' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function mount(): void
    {
        $this->authorizePermission('maintenance.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDue(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';
        $structureId = $this->scopedStructureId();

        $plans = MaintenancePlan::query()
            ->with(['equipment.structure', 'equipment.location'])
            ->when($structureId, fn ($query) => $query->whereHas('equipment', fn ($equipmentQuery) => $equipmentQuery->where('structure_id', $structureId)))
            ->when($this->status !== '', fn ($query) => $query->where('statut', $this->status))
            ->when($this->due === 'overdue', fn ($query) => $query->whereNotNull('prochain')->whereDate('prochain', '<', now()))
            ->when($this->due === 'soon', fn ($query) => $query->whereBetween('prochain', [now()->startOfDay(), now()->addDays(14)->endOfDay()]))
            ->when($this->search !== '', function ($query) use ($search) {
                $query->whereHas('equipment', function ($equipmentQuery) use ($search) {
                    $equipmentQuery->where('code_inventaire', 'like', $search)
                        ->orWhere('marque', 'like', $search)
                        ->orWhere('modele', 'like', $search)
                        ->orWhereHas('translations', fn ($translationQuery) => $translationQuery->where('nom', 'like', $search));
                });
            })
            ->orderBy('prochain')
            ->paginate($this->perPage);

        return view('livewire.maintenance.index', [
            'plans' => $plans,
        ])->layout('layouts.app');
    }
}
