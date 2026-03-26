<?php

namespace App\Livewire\Intervenant;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Intervenant;
use Livewire\Component;
use Livewire\WithPagination;

class IntervenantIndex extends Component
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
        $this->authorizePermission('intervenants.view');
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();
        $search = '%'.$this->search.'%';

        $intervenants = Intervenant::query()
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->when($this->status !== '', fn ($q) => $q->where('actif', $this->status === '1'))
            ->when($this->search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('nom', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('telephone', 'like', $search)
                        ->orWhere('specialite', 'like', $search);
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.intervenant.index', [
            'intervenants' => $intervenants,
        ])->layout('layouts.app');
    }

    public function delete(int $intervenantId): void
    {
        $this->authorizePermission('intervenants.delete');
        $structureId = $this->scopedStructureId();

        $intervenant = Intervenant::query()
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->findOrFail($intervenantId);

        $intervenant->delete();

        $this->dispatch('notify', message: 'Intervenant supprimé');
        $this->resetPage();
    }
}
