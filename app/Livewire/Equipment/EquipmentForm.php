<?php

namespace App\Livewire\Equipment;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentLocationHistory;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\User;
use App\Livewire\Forms\EquipmentForm as EquipmentFormObject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EquipmentForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $equipmentId = null;
    public EquipmentFormObject $form;

    public function mount(?int $equipmentId = null): void
    {
        $this->equipmentId = $equipmentId;
        $this->form->structure_id = $this->scopedStructureId() ?? Auth::user()?->structure_id;

        if ($equipmentId) {
            $equipment = Equipment::findOrFail($equipmentId);
            $this->authorizeRecordAccess($equipment, 'equipments.update');
            $this->form->setEquipment($equipment);
            return;
        }

        $this->authorizePermission('equipments.create');
    }

    public function save(): void
    {
        $existingEquipment = $this->equipmentId ? Equipment::findOrFail($this->equipmentId) : null;

        if ($existingEquipment) {
            $this->authorizeRecordAccess($existingEquipment, 'equipments.update');
        } else {
            $this->authorizePermission('equipments.create');
        }

        $structureId = $this->scopedStructureId();
        if ($structureId !== null) {
            $this->form->structure_id = $structureId;
        }

        $this->form->store();

        $this->dispatch('notify', message: 'Équipement enregistré');
        $this->redirect(route('equipments.index', [], false));
    }

    public function render()
    {
        $locationHistories = collect();

        if ($this->equipmentId) {
            $locationHistories = EquipmentLocationHistory::query()
                ->with('user')
                ->where('equipment_id', $this->equipmentId)
                ->orderByDesc('changed_at')
                ->limit(20)
                ->get();
        }

        $structureId = $this->scopedStructureId();

        return view('livewire.equipment.form', [
            'types' => EquipmentType::all(),
            'categories' => EquipmentCategory::all(),
            'suppliers' => Supplier::all(),
            'users' => User::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->orderBy('name')
                ->get(),
            'locations' => Location::query()
                ->when($structureId, fn ($query, $sid) => $query->where('structure_id', $sid))
                ->orderBy('type')
                ->orderBy('nom')
                ->get(),
            'locationHistories' => $locationHistories,
        ])->layout('layouts.app');
    }
}
