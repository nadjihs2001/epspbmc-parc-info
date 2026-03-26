<?php

namespace App\Services\AI;

use App\Models\AiRequest;
use App\Models\Ticket;

class AiAssistantService
{
    public function diagnoseTicket(Ticket $ticket): AiRequest
    {
        $equipment = $ticket->equipment;

        $payload = [
            'type' => 'diagnostic',
            'ticket_code' => $ticket->code,
            'description' => $ticket->description,
            'equipment' => $equipment ? [
                'name' => $equipment->nom,
                'type' => $equipment->type?->nom,
                'category' => $equipment->category?->nom,
                'marque' => $equipment->marque,
                'modele' => $equipment->modele,
                'os' => $equipment->os,
                'age' => $equipment->date_acquisition ? $equipment->date_acquisition->diffForHumans() : 'Inconnu',
                'notes' => $equipment->notes,
            ] : null,
            'previous_tickets_count' => $equipment ? Ticket::where('equipment_id', $equipment->id)->count() : 0,
        ];

        $response = $this->callProvider($payload);

        return AiRequest::create([
            'user_id' => auth()->id(),
            'type' => 'diagnostic',
            'payload' => $payload,
            'response' => $response,
            'status' => 'completed',
        ]);
    }

    protected function callProvider(array $payload): array
    {
        // Mocking an AI response for demonstration
        // In a real scenario, this would call an API like OpenAI or Claude

        $description = strtolower($payload['description']);
        $equipmentType = $payload['equipment']['type'] ?? 'Inconnu';

        $suggestedPriority = 'moyenne';
        $causes = ['Problème matériel indéterminé', 'Erreur logicielle'];
        $actions = ['Vérifier les connexions physiques', 'Redémarrer l\'équipement'];

        if (str_contains($description, 'allume') || str_contains($description, 'noir') || str_contains($description, 'power')) {
            $suggestedPriority = 'haute';
            $causes = ['Bloc d\'alimentation défaillant', 'Câble secteur déconnecté', 'Composant interne HS'];
            $actions = ['Tester avec un autre câble', 'Vérifier la prise murale', 'Ouvrir pour inspecter les condensateurs'];
        } elseif (str_contains($description, 'lent') || str_contains($description, 'ram') || str_contains($description, 'vitesse')) {
            $causes = ['Mémoire saturée', 'Processus gourmand en arrière-plan', 'Disque dur en fin de vie'];
            $actions = ['Nettoyage des fichiers temporaires', 'Ajout de RAM si possible', 'Remplacement par un SSD'];
        } elseif (str_contains($description, 'internet') || str_contains($description, 'réseau') || str_contains($description, 'wifi')) {
            $suggestedPriority = 'haute';
            $causes = ['Carte réseau désactivée', 'Pilote obsolète', 'Problème de configuration IP'];
            $actions = ['Réinitialiser la pile TCP/IP', 'Mettre à jour les pilotes', 'Vérifier l\'attribution DHCP'];
        }

        return [
            'suggested_priority' => $suggestedPriority,
            'probable_causes' => $causes,
            'recommended_actions' => $actions,
            'ai_analysis' => "Analyse effectuée pour un(e) {$equipmentType}. L'équipement présente des symptômes liés à: " . implode(', ', $causes) . ".",
        ];
    }
}
