<?php

namespace App\Livewire\License;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\SoftwareLicense;
use Livewire\Component;

class LicenseForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $licenseId = null;

    public array $form = [
        'nom' => '',
        'type' => '',
        'cle' => '',
        'nb_installations' => 1,
        'expiration' => null,
        'alert_days' => 30,
        'structure_id' => null,
    ];

    public function mount(?int $licenseId = null): void
    {
        $this->licenseId = $licenseId;
        $this->form['structure_id'] = $this->scopedStructureId();

        if ($licenseId) {
            $license = SoftwareLicense::findOrFail($licenseId);
            $this->authorizeRecordAccess($license, 'licenses.update');
            $this->form = array_merge($this->form, $license->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('licenses.create');
    }

    public function save(): void
    {
        $license = $this->licenseId ? SoftwareLicense::findOrFail($this->licenseId) : null;

        if ($license) {
            $this->authorizeRecordAccess($license, 'licenses.update');
        } else {
            $this->authorizePermission('licenses.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);

        $this->validate([
            'form.nom' => 'required|string',
            'form.structure_id' => 'required|exists:structures,id',
        ]);

        SoftwareLicense::updateOrCreate(
            ['id' => $this->licenseId],
            $this->form
        );

        $this->dispatch('notify', message: 'Licence enregistrée');
        $this->redirect(route('licenses.index', [], false));
    }

    public function render()
    {
        return view('livewire.license.form')->layout('layouts.app');
    }
}
