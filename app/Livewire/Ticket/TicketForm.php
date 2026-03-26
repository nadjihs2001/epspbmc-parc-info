<?php

namespace App\Livewire\Ticket;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Equipment;
use App\Models\Intervenant;
use App\Models\Ticket;
use App\Livewire\Forms\TicketForm as TicketFormObject;
use App\Services\AI\AiAssistantService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $ticketId = null;
    public TicketFormObject $form;

    public ?array $aiResults = null;
    public bool $isDiagnosing = false;

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

    public function runAiDiagnosis(AiAssistantService $aiService): void
    {
        if (empty($this->form->description)) {
            $this->addError('form.description', 'Veuillez d\'abord décrire le problème.');
            return;
        }

        $this->isDiagnosing = true;

        // Temporarily save or create a ticket for AI diagnosis if it doesn't exist
        $tempTicket = $this->ticketId ? Ticket::find($this->ticketId) : new Ticket($this->form->all());
        if ($this->form->equipment_id) {
            $tempTicket->equipment_id = $this->form->equipment_id;
        }

        try {
            $aiRequest = $aiService->diagnoseTicket($tempTicket);
            $this->aiResults = $aiRequest->response;

            // Auto-apply suggested priority if user hasn't changed it or it's high
            if ($this->aiResults['suggested_priority'] === 'haute' || $this->aiResults['suggested_priority'] === 'critique') {
                $this->form->priorite = $this->aiResults['suggested_priority'];
            }

            $this->dispatch('notify', message: 'Diagnostic IA terminé');
        } catch (\Exception $e) {
            $this->addError('ai', 'Erreur lors du diagnostic IA: ' . $e->getMessage());
        } finally {
            $this->isDiagnosing = false;
        }
    }

    public function applyAiSuggestion(): void
    {
        if (!$this->aiResults) return;

        $suggestion = "\n\n--- Suggestion IA ---\n";
        $suggestion .= "Analyse: " . ($this->aiResults['ai_analysis'] ?? 'N/A') . "\n";
        $suggestion .= "Causes probables: " . implode(', ', $this->aiResults['probable_causes'] ?? []) . "\n";
        $suggestion .= "Actions recommandées: " . implode(', ', $this->aiResults['recommended_actions'] ?? []);

        $this->form->description .= $suggestion;
        $this->aiResults = null;
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
