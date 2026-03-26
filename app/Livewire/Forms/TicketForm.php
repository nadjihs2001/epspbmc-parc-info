<?php

namespace App\Livewire\Forms;

use App\Models\Ticket;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TicketForm extends Form
{
    public ?Ticket $ticket = null;

    public $code = '';
    public $equipment_id = null;
    public $structure_id = null;
    public $user_id = null;
    public $type = 'panne';
    public $intervenant_id = null;
    public $priorite = 'moyenne';
    public $statut = 'ouvert';
    public $description = '';

    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;

        $this->code = $ticket->code;
        $this->equipment_id = $ticket->equipment_id;
        $this->structure_id = $ticket->structure_id;
        $this->user_id = $ticket->user_id;
        $this->type = $ticket->type;
        $this->intervenant_id = $ticket->intervenant_id;
        $this->priorite = $ticket->priorite;
        $this->statut = $ticket->statut;
        $this->description = $ticket->description;
    }

    public function rules()
    {
        return [
            'code' => 'required|unique:tickets,code,' . ($this->ticket?->id ?? 'NULL'),
            'equipment_id' => [
                'nullable',
                Rule::exists('equipments', 'id')->where(fn ($q) => $q->where('structure_id', $this->structure_id)),
            ],
            'structure_id' => 'required|exists:structures,id',
            'type' => 'required|in:panne,maintenance_preventive',
            'description' => 'required|string',
            'intervenant_id' => [
                'nullable',
                Rule::exists('intervenants', 'id')->where(fn ($q) => $q->where('structure_id', $this->structure_id)),
            ],
            'priorite' => 'required|in:basse,moyenne,haute,critique',
            'statut' => 'required|in:ouvert,en_cours,en_attente,resolu,clos',
        ];
    }

    public function store()
    {
        $this->validate();

        $data = $this->all();
        unset($data['ticket']);

        if ($this->ticket) {
            $this->ticket->update($data);
            return $this->ticket;
        }

        return Ticket::create($data);
    }
}
