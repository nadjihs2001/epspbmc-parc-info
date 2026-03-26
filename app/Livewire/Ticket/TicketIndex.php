<?php

namespace App\Livewire\Ticket;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class TicketIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public string $status = '';
    public string $priority = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
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

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingPriority(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorizePermission('tickets.view');
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();
        $search = '%'.$this->search.'%';

        $tickets = Ticket::query()
            ->with(['equipment', 'user', 'intervenant'])
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->when($this->type !== '', fn ($q) => $q->where('type', $this->type))
            ->when($this->status !== '', fn ($q) => $q->where('statut', $this->status))
            ->when($this->priority !== '', fn ($q) => $q->where('priorite', $this->priority))
            ->when($this->search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('code', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhereHas('equipment', fn ($eq) => $eq->where('code_inventaire', 'like', $search))
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', $search))
                        ->orWhereHas('intervenant', fn ($intervenant) => $intervenant->where('nom', 'like', $search));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.ticket.index', [
            'tickets' => $tickets,
        ])->layout('layouts.app');
    }
}
