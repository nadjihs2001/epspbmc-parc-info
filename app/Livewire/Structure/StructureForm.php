<?php

namespace App\Livewire\Structure;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Structure;
use Livewire\Component;

class StructureForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $structureId = null;

    public array $form = [
        'parent_id' => null,
        'nom' => '',
        'type' => 'service',
        'code' => '',
        'adresse' => '',
        'responsable_nom' => '',
        'actif' => true,
    ];

    public function mount(?int $structureId = null): void
    {
        $this->structureId = $structureId;

        if ($structureId) {
            $this->authorizePermission('structures.update');
            $structure = Structure::findOrFail($structureId);
            $this->form = array_merge($this->form, $structure->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('structures.create');
    }

    public function save(): void
    {
        $this->authorizePermission($this->structureId ? 'structures.update' : 'structures.create');

        $this->validate([
            'form.parent_id' => 'nullable|exists:structures,id',
            'form.nom' => 'required|string|max:255',
            'form.type' => 'required|string|max:100',
            'form.code' => 'nullable|string|max:100|unique:structures,code,' . $this->structureId,
            'form.adresse' => 'nullable|string|max:255',
            'form.responsable_nom' => 'nullable|string|max:255',
            'form.actif' => 'boolean',
        ]);

        Structure::updateOrCreate(
            ['id' => $this->structureId],
            $this->form
        );

        $this->dispatch('notify', message: 'Structure enregistree');
        $this->redirect(route('structures.index', [], false));
    }

    public function render()
    {
        return view('livewire.structure.form', [
            'parents' => Structure::query()
                ->when($this->structureId, fn ($query) => $query->where('id', '!=', $this->structureId))
                ->orderBy('nom')
                ->get(),
        ])->layout('layouts.app');
    }
}
