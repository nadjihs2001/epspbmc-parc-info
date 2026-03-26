<?php

namespace App\Livewire\Network;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\IpRange;
use Livewire\Component;

class IpRangeForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $rangeId = null;

    public array $form = [
        'vlan_id' => null,
        'cidr' => '',
        'passerelle' => '',
        'dns1' => '',
        'dns2' => '',
        'structure_id' => null,
    ];

    public function mount(?int $rangeId = null): void
    {
        $this->rangeId = $rangeId;
        $this->form['structure_id'] = $this->scopedStructureId();

        if ($rangeId) {
            $range = IpRange::findOrFail($rangeId);
            $this->authorizeRecordAccess($range, 'network.update');
            $this->form = array_merge($this->form, $range->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('network.update');
    }

    public function save(): void
    {
        $range = $this->rangeId ? IpRange::findOrFail($this->rangeId) : null;

        if ($range) {
            $this->authorizeRecordAccess($range, 'network.update');
        } else {
            $this->authorizePermission('network.update');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);

        $this->validate([
            'form.cidr' => 'required|string',
            'form.structure_id' => 'required|exists:structures,id',
        ]);

        IpRange::updateOrCreate(
            ['id' => $this->rangeId],
            $this->form
        );

        $this->dispatch('notify', message: 'Plage IP enregistrée');
        $this->redirect(route('network.ranges.index', [], false));
    }

    public function render()
    {
        return view('livewire.network.ip-range-form')->layout('layouts.app');
    }
}
