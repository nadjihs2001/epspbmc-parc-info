<?php

namespace App\Livewire\Assignment;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Assignment;
use Livewire\Component;
use Livewire\WithPagination;

class AssignmentIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $actif = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'actif' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActif(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorizePermission('assignments.view');
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();
        $search = '%'.$this->search.'%';

        $assignments = Assignment::query()
            ->with(['equipment', 'user', 'location.structure', 'location.department', 'location.parent'])
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->when($this->actif !== '', fn ($q) => $q->where('actif', $this->actif === '1'))
            ->when($this->search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->whereHas('equipment', fn ($eq) => $eq->where('code_inventaire', 'like', $search))
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', $search))
                        ->orWhereHas('location', fn ($locationQuery) => $locationQuery->where('nom', 'like', $search))
                        ->orWhere('justification', 'like', $search);
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.assignment.index', [
            'assignments' => $assignments,
        ])->layout('layouts.app');
    }
}
