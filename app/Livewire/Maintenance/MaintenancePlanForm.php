<?php

namespace App\Livewire\Maintenance;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Equipment;
use App\Models\MaintenancePlan;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MaintenancePlanForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $planId = null;

    public array $form = [
        'equipment_id' => null,
        'periodicite_jours' => 90,
        'prochain' => null,
        'statut' => 'actif',
    ];

    public function mount(?int $planId = null): void
    {
        $this->planId = $planId;

        if ($planId) {
            $plan = MaintenancePlan::query()->with('equipment')->findOrFail($planId);
            $this->authorizeRecordAccess($plan->equipment, 'maintenance.manage');
            $this->form = array_merge($this->form, $plan->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('maintenance.manage');
    }

    public function save(): void
    {
        $plan = $this->planId ? MaintenancePlan::query()->with('equipment')->findOrFail($this->planId) : null;
        $structureId = $this->scopedStructureId();

        if ($plan) {
            $this->authorizeRecordAccess($plan->equipment, 'maintenance.manage');
        } else {
            $this->authorizePermission('maintenance.manage');
        }

        $this->validate([
            'form.equipment_id' => [
                'required',
                Rule::exists('equipments', 'id')->where(function ($query) use ($structureId) {
                    if ($structureId) {
                        $query->where('structure_id', $structureId);
                    }
                }),
            ],
            'form.periodicite_jours' => 'required|integer|min:1|max:3650',
            'form.prochain' => 'nullable|date',
            'form.statut' => 'required|in:actif,suspendu',
        ]);

        MaintenancePlan::updateOrCreate(
            ['id' => $this->planId],
            $this->form
        );

        $this->dispatch('notify', message: 'Plan de maintenance enregistre');
        $this->redirect(route('maintenance.index', [], false));
    }

    public function render()
    {
        return view('livewire.maintenance.form', [
            'equipments' => Equipment::query()
                ->when($this->scopedStructureId(), fn ($query, $structureId) => $query->where('structure_id', $structureId))
                ->orderBy('code_inventaire')
                ->get(),
        ])->layout('layouts.app');
    }
}
