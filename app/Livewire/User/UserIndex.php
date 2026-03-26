<?php

namespace App\Livewire\User;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $role = '';
    public string $status = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
        'status' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function mount(): void
    {
        $this->authorizePermission('users.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
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

    public function delete(int $userId): void
    {
        $this->authorizePermission('users.delete');

        $user = User::query()
            ->when($this->scopedStructureId(), fn ($query, $structureId) => $query->where('structure_id', $structureId))
            ->findOrFail($userId);

        abort_if($user->is(auth()->user()), 422, 'Vous ne pouvez pas supprimer votre propre compte.');

        $user->delete();

        $this->dispatch('notify', message: 'Utilisateur supprime');
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';
        $structureId = $this->scopedStructureId();

        $users = User::query()
            ->with(['roles', 'structure', 'department'])
            ->when($structureId, fn ($query) => $query->where('structure_id', $structureId))
            ->when($this->status !== '', fn ($query) => $query->where('actif', $this->status === '1'))
            ->when($this->role !== '', fn ($query) => $query->role($this->role))
            ->when($this->search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('fonction', 'like', $search)
                        ->orWhere('telephone', 'like', $search);
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.user.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
