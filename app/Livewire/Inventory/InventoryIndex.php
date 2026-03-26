<?php

namespace App\Livewire\Inventory;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\EquipmentType;
use App\Models\Structure;
use App\Services\Inventory\InventoryReportService;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $statut = '';
    public string $structure_id = '';
    public string $type_id = '';
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'statut' => ['except' => ''],
        'structure_id' => ['except' => ''],
        'type_id' => ['except' => ''],
        'perPage' => ['except' => 20],
    ];

    public function mount(): void
    {
        $this->authorizePermission('inventory.view');

        if ($this->scopedStructureId()) {
            $this->structure_id = (string) $this->scopedStructureId();
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatut(): void
    {
        $this->resetPage();
    }

    public function updatingStructureId(): void
    {
        $this->resetPage();
    }

    public function updatingTypeId(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function render(InventoryReportService $inventoryReportService)
    {
        $filters = [
            'search' => $this->search,
            'statut' => $this->statut,
            'structure_id' => $this->structure_id !== '' ? (int) $this->structure_id : null,
            'type_id' => $this->type_id !== '' ? (int) $this->type_id : null,
        ];

        $summary = $inventoryReportService->summary($filters, auth()->user());
        $equipments = $inventoryReportService->query($filters, auth()->user())->paginate($this->perPage);

        return view('livewire.inventory.index', [
            'summary' => $summary,
            'equipments' => $equipments,
            'structures' => Structure::query()
                ->when($this->scopedStructureId(), fn ($query, $structureId) => $query->where('id', $structureId))
                ->orderBy('nom')
                ->get(),
            'types' => EquipmentType::query()->orderBy('id')->get(),
        ])->layout('layouts.app');
    }
}
