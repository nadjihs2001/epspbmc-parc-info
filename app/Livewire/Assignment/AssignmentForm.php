<?php

namespace App\Livewire\Assignment;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Assignment;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AssignmentForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $assignmentId = null;

    public array $form = [
        'equipment_id' => null,
        'user_id' => null,
        'structure_id' => null,
        'location_id' => null,
        'date_debut' => null,
        'date_fin' => null,
        'justification' => null,
    ];

    public function mount(?int $assignmentId = null): void
    {
        $this->assignmentId = $assignmentId;
        $this->form['structure_id'] = $this->scopedStructureId();

        if ($assignmentId) {
            $assignment = Assignment::findOrFail($assignmentId);
            $this->authorizeRecordAccess($assignment, 'assignments.update');
            $this->form = array_merge($this->form, $assignment->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('assignments.create');
    }

    public function save(): void
    {
        $assignment = $this->assignmentId ? Assignment::findOrFail($this->assignmentId) : null;

        if ($assignment) {
            $this->authorizeRecordAccess($assignment, 'assignments.update');
        } else {
            $this->authorizePermission('assignments.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);
        $structureId = $this->form['structure_id'];

        $this->validate([
            'form.equipment_id' => [
                'required',
                Rule::exists('equipments', 'id')->where(fn ($q) => $q->where('structure_id', $structureId)),
            ],
            'form.user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('structure_id', $structureId)),
            ],
            'form.structure_id' => 'required|exists:structures,id',
            'form.location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($q) => $q->where('structure_id', $structureId)),
            ],
            'form.date_fin' => 'nullable|date|after_or_equal:form.date_debut',
        ]);

        Assignment::updateOrCreate(
            ['id' => $this->assignmentId],
            $this->form
        );

        $this->dispatch('notify', message: 'Affectation enregistrée');
        $this->redirect(route('assignments.index', [], false));
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();

        return view('livewire.assignment.form', [
            'equipments' => Equipment::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->orderBy('code_inventaire')
                ->get(),
            'locations' => Location::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->orderBy('type')
                ->orderBy('nom')
                ->get(),
            'users' => User::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.app');
    }
}
