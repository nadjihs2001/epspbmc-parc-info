<?php

namespace App\Livewire\Location;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Department;
use App\Models\Location;
use App\Models\Structure;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LocationForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $locationId = null;

    public array $form = [
        'structure_id' => null,
        'department_id' => null,
        'parent_id' => null,
        'nom' => '',
        'type' => 'bureau',
        'code' => '',
        'etage' => '',
        'details' => '',
        'actif' => true,
    ];

    public function mount(?int $locationId = null): void
    {
        $this->locationId = $locationId;
        $this->form['structure_id'] = $this->scopedStructureId();

        if ($locationId) {
            $location = Location::findOrFail($locationId);
            $this->authorizeRecordAccess($location, 'locations.update');
            $this->form = array_merge($this->form, $location->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('locations.create');
    }

    public function save(): void
    {
        $location = $this->locationId ? Location::findOrFail($this->locationId) : null;

        if ($location) {
            $this->authorizeRecordAccess($location, 'locations.update');
        } else {
            $this->authorizePermission('locations.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);

        $this->validate([
            'form.structure_id' => 'required|exists:structures,id',
            'form.department_id' => [
                'nullable',
                Rule::exists('departments', 'id')->where(fn ($query) => $query->where('structure_id', $this->form['structure_id'])),
            ],
            'form.parent_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($query) => $query->where('structure_id', $this->form['structure_id'])),
            ],
            'form.nom' => 'required|string|max:255',
            'form.type' => 'required|string|max:100',
            'form.code' => 'nullable|string|max:100|unique:locations,code,' . $this->locationId,
            'form.etage' => 'nullable|string|max:50',
            'form.details' => 'nullable|string|max:1000',
            'form.actif' => 'boolean',
        ]);

        Location::updateOrCreate(
            ['id' => $this->locationId],
            $this->form
        );

        $this->dispatch('notify', message: 'Local enregistre');
        $this->redirect(route('locations.index', [], false));
    }

    public function render()
    {
        $scopedStructureId = $this->scopedStructureId();

        return view('livewire.location.form', [
            'structures' => Structure::query()
                ->when($scopedStructureId, fn ($query, $structureId) => $query->where('id', $structureId))
                ->orderBy('nom')
                ->get(),
            'departments' => Department::query()
                ->when($this->form['structure_id'], fn ($query, $structureId) => $query->where('structure_id', $structureId))
                ->orderBy('nom')
                ->get(),
            'parents' => Location::query()
                ->when($this->form['structure_id'], fn ($query, $structureId) => $query->where('structure_id', $structureId))
                ->when($this->locationId, fn ($query) => $query->where('id', '!=', $this->locationId))
                ->orderBy('nom')
                ->get(),
        ])->layout('layouts.app');
    }
}
