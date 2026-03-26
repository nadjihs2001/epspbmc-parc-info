<?php

namespace App\Livewire\Ticket;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Equipment;
use App\Models\Intervenant;
use App\Models\Ticket;
use App\Livewire\Forms\TicketForm as TicketFormObject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $ticketId = null;
    public TicketFormObject $form;

    public function mount(?int $ticketId = null): void
    {
        $this->ticketId = $ticketId;
        $this->form->structure_id = $this->scopedStructureId() ?? Auth::user()?->structure_id;
        $this->form->user_id = Auth::id();

        if ($ticketId) {
            $ticket = Ticket::findOrFail($ticketId);
            $this->authorizeRecordAccess($ticket, 'tickets.update');
            $this->form->setTicket($ticket);
            return;
        }

        $this->authorizePermission('tickets.create');
    }

    public function save(): void
    {
        $existingTicket = $this->ticketId ? Ticket::findOrFail($this->ticketId) : null;

        if ($existingTicket) {
            $this->authorizeRecordAccess($existingTicket, 'tickets.update');
        } else {
            $this->authorizePermission('tickets.create');
        }

        $structureId = $this->scopedStructureId();
        if ($structureId !== null) {
            $this->form->structure_id = $structureId;
        }

        $this->form->store();

        $this->dispatch('notify', message: 'Ticket enregistré');
        $this->redirect(route('tickets.index', [], false));
    }

    public function render()
    {
        $structureId = $this->scopedStructureId();

        return view('livewire.ticket.form', [
            'equipments' => Equipment::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->orderBy('code_inventaire')
                ->get(),
            'intervenants' => Intervenant::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->where(function ($q) {
                    $q->where('actif', true);

                    if (!empty($this->form->intervenant_id)) {
                        $q->orWhere('id', $this->form->intervenant_id);
                    }
                })
                ->orderBy('nom')
                ->get(),
        ])->layout('layouts.app');
    }
}
