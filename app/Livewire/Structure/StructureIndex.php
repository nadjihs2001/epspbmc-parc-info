<?php

namespace App\Livewire\Structure;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Structure;
use Livewire\Component;
use Livewire\WithPagination;

class StructureIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function mount(): void
    {
        $this->authorizePermission('structures.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
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
        $scopedStructureId = $this->scopedStructureId();

        $structures = Structure::query()
            ->with('parent')
            ->when($scopedStructureId, function ($query, $structureId) {
                $query->where(function ($nested) use ($structureId) {
                    $nested->where('id', $structureId)
                        ->orWhere('parent_id', $structureId);
                });
            })
            ->when($this->type !== '', fn ($query) => $query->where('type', $this->type))
            ->when($this->search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('nom', 'like', $search)
                        ->orWhere('code', 'like', $search)
                        ->orWhere('adresse', 'like', $search)
                        ->orWhere('responsable_nom', 'like', $search);
                });
            })
            ->orderBy('nom')
            ->paginate($this->perPage);

        return view('livewire.structure.index', [
            'structures' => $structures,
        ])->layout('layouts.app');
    }
}
