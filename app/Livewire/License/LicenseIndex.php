<?php

namespace App\Livewire\License;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\SoftwareLicense;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class LicenseIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $expiration = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'expiration' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingExpiration(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorizePermission('licenses.view');
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();
        $search = '%'.$this->search.'%';
        $today = Carbon::today();
        $soon = Carbon::today()->addDays(30);

        $licenses = SoftwareLicense::query()
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->when($this->search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('nom', 'like', $search)
                        ->orWhere('type', 'like', $search)
                        ->orWhere('cle', 'like', $search);
                });
            })
            ->when($this->expiration === 'expired', fn ($q) => $q->whereNotNull('expiration')->whereDate('expiration', '<', $today))
            ->when($this->expiration === 'soon', fn ($q) => $q->whereBetween('expiration', [$today, $soon]))
            ->when($this->expiration === 'none', fn ($q) => $q->whereNull('expiration'))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.license.index', [
            'licenses' => $licenses,
        ])->layout('layouts.app');
    }
}
