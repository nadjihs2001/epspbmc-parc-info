<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\AI\AiAssistantService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function diagnose(Request $request, AiAssistantService $service)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $ticket = Ticket::findOrFail($validated['ticket_id']);
        $result = $service->diagnoseTicket($ticket);

        return response()->json($result);
    }
}
