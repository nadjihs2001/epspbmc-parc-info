<?php

namespace App\Livewire\Ticket;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Intervenant;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TicketForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $ticketId = null;

    public array $form = [
        'code' => '',
        'equipment_id' => null,
        'structure_id' => null,
        'user_id' => null,
        'type' => 'panne',
        'intervenant_id' => null,
        'priorite' => 'moyenne',
        'statut' => 'ouvert',
        'description' => '',
    ];

    public function mount(?int $ticketId = null): void
    {
        $this->ticketId = $ticketId;
        $this->form['structure_id'] = $this->scopedStructureId() ?? Auth::user()?->structure_id;
        $this->form['user_id'] = Auth::id();

        if ($ticketId) {
            $ticket = Ticket::findOrFail($ticketId);
            $this->authorizeRecordAccess($ticket, 'tickets.update');
            $this->form = array_merge($this->form, $ticket->only(array_keys($this->form)));

            return;
        }

        $this->authorizePermission('tickets.create');
    }

    public function save(): void
    {
        $ticket = $this->ticketId ? Ticket::findOrFail($this->ticketId) : null;

        if ($ticket) {
            $this->authorizeRecordAccess($ticket, 'tickets.update');
        } else {
            $this->authorizePermission('tickets.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);
        $structureId = $this->form['structure_id'];

        $this->validate([
            'form.code' => 'required|unique:tickets,code,' . $this->ticketId,
            'form.structure_id' => 'required|exists:structures,id',
            'form.type' => 'required|in:panne,maintenance_preventive',
            'form.description' => 'required|string',
            'form.intervenant_id' => [
                'nullable',
                Rule::exists('intervenants', 'id')->where(fn ($q) => $q->where('structure_id', $structureId)),
            ],
        ]);

        Ticket::updateOrCreate(
            ['id' => $this->ticketId],
            $this->form
        );

        $this->dispatch('notify', message: 'Ticket enregistré');
        $this->redirect(route('tickets.index', [], false));
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();

        return view('livewire.ticket.form', [
            'intervenants' => Intervenant::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->where(function ($q) {
                    $q->where('actif', true);

                    if (!empty($this->form['intervenant_id'])) {
                        $q->orWhere('id', $this->form['intervenant_id']);
                    }
                })
                ->orderBy('nom')
                ->get(),
        ])->layout('layouts.app');
    }
}
