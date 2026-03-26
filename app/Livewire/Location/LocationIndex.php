<?php

namespace App\Livewire\Location;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class LocationIndex extends Component
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
        $this->authorizePermission('locations.view');
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

        $locations = Location::query()
            ->with(['structure', 'department', 'parent'])
            ->when($this->scopedStructureId(), fn ($query, $structureId) => $query->where('structure_id', $structureId))
            ->when($this->type !== '', fn ($query) => $query->where('type', $this->type))
            ->when($this->search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('nom', 'like', $search)
                        ->orWhere('code', 'like', $search)
                        ->orWhere('details', 'like', $search)
                        ->orWhereHas('structure', fn ($structureQuery) => $structureQuery->where('nom', 'like', $search));
                });
            })
            ->orderBy('nom')
            ->paginate($this->perPage);

        return view('livewire.location.index', [
            'locations' => $locations,
        ])->layout('layouts.app');
    }
}
