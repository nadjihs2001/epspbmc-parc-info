<?php

namespace App\Livewire\Network;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\IpRange;
use Livewire\Component;
use Livewire\WithPagination;

class IpRangeIndex extends Component
{
    use AuthorizesModuleAccess;
    use WithPagination;

    public string $search = '';
    public string $vlan = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'vlan' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingVlan(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorizePermission('network.view');
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();
        $search = '%'.$this->search.'%';

        $ranges = IpRange::query()
            ->with('vlan')
            ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
            ->when($this->vlan === 'with', fn ($q) => $q->whereNotNull('vlan_id'))
            ->when($this->vlan === 'without', fn ($q) => $q->whereNull('vlan_id'))
            ->when($this->search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('cidr', 'like', $search)
                        ->orWhere('passerelle', 'like', $search)
                        ->orWhere('dns1', 'like', $search)
                        ->orWhere('dns2', 'like', $search);
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.network.ip-ranges', [
            'ranges' => $ranges,
        ])->layout('layouts.app');
    }
}
