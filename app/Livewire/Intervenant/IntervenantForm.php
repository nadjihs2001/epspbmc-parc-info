<?php

namespace App\Livewire\Intervenant;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Intervenant;
use Livewire\Component;

class IntervenantForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $intervenantId = null;

    public array $form = [
        'nom' => '',
        'email' => '',
        'telephone' => '',
        'specialite' => '',
        'structure_id' => null,
        'actif' => true,
    ];

    public function mount(?int $intervenantId = null): void
    {
        $this->intervenantId = $intervenantId;
        $this->form['structure_id'] = $this->scopedStructureId();

        if ($intervenantId) {
            $intervenant = Intervenant::findOrFail($intervenantId);
            $this->authorizeRecordAccess($intervenant, 'intervenants.update');
            $this->form = array_merge($this->form, $intervenant->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('intervenants.create');
    }

    public function save(): void
    {
        $intervenant = $this->intervenantId ? Intervenant::findOrFail($this->intervenantId) : null;

        if ($intervenant) {
            $this->authorizeRecordAccess($intervenant, 'intervenants.update');
        } else {
            $this->authorizePermission('intervenants.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);

        $this->validate([
            'form.nom' => 'required|string|max:255',
            'form.email' => 'nullable|email|max:255|unique:intervenants,email,' . $this->intervenantId,
            'form.telephone' => 'nullable|string|max:50',
            'form.specialite' => 'nullable|string|max:255',
            'form.structure_id' => 'required|exists:structures,id',
            'form.actif' => 'boolean',
        ]);

        Intervenant::updateOrCreate(
            ['id' => $this->intervenantId],
            $this->form
        );

        $this->dispatch('notify', message: 'Intervenant enregistré');
        $this->redirect(route('intervenants.index', [], false));
    }

    public function render()
    {
        return view('livewire.intervenant.form')->layout('layouts.app');
    }
}
