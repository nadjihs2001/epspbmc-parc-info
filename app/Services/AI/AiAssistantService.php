<?php

namespace App\Services\AI;

use App\Models\AiRequest;
use App\Models\Ticket;

class AiAssistantService
{
    public function diagnoseTicket(Ticket $ticket): AiRequest
    {
        $payload = [
            'type' => 'diagnostic',
            'description' => $ticket->description,
            'equipment' => $ticket->equipment?->toArray(),
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
        return [
            'suggested_priority' => 'haute',
            'probable_causes' => ['surchauffe', 'alimentation défaillante'],
            'recommended_actions' => ['vérifier ventilation', 'tester alimentation'],
        ];
    }
}
