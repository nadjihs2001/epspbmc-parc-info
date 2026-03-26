<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Tickets</h1>
        <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('tickets.create') }}">
            Nouveau ticket
        </a>
    </div>

    <div class="grid gap-3 md:grid-cols-6">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche code, description, équipement, intervenant..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="type" class="w-full px-3 py-2 border rounded">
                <option value="">Tous types</option>
                <option value="panne">Panne</option>
                <option value="maintenance_preventive">Maintenance preventive</option>
            </select>
        </div>
        <div>
            <select wire:model.live="status" class="w-full px-3 py-2 border rounded">
                <option value="">Tous statuts</option>
                <option value="ouvert">Ouvert</option>
                <option value="en_cours">En cours</option>
                <option value="en_attente">En attente</option>
                <option value="resolu">Résolu</option>
                <option value="clos">Clos</option>
            </select>
        </div>
        <div>
            <select wire:model.live="priority" class="w-full px-3 py-2 border rounded">
                <option value="">Toutes priorités</option>
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
                <option value="critique">Critique</option>
            </select>
        </div>
        <div>
            <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded">
                <option value="10">10 / page</option>
                <option value="15">15 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Équipement</th>
                    <th class="px-4 py-3 text-left">Intervenant</th>
                    <th class="px-4 py-3 text-left">Priorité</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Ouvert le</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($tickets as $ticket)
                    @php
                        $priorityClass = match ($ticket->priorite) {
                            'critique' => 'bg-rose-100 text-rose-700',
                            'haute' => 'bg-amber-100 text-amber-700',
                            'moyenne' => 'bg-sky-100 text-sky-700',
                            default => 'bg-slate-100 text-slate-700',
                        };

                        $statusClass = match ($ticket->statut) {
                            'resolu' => 'bg-emerald-100 text-emerald-700',
                            'clos' => 'bg-slate-100 text-slate-700',
                            'en_cours' => 'bg-indigo-100 text-indigo-700',
                            'en_attente' => 'bg-amber-100 text-amber-700',
                            default => 'bg-rose-100 text-rose-700',
                        };
                    @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $ticket->code }}</td>
                        <td class="px-4 py-3">{{ $ticket->type === 'maintenance_preventive' ? 'Maintenance preventive' : 'Panne' }}</td>
                        <td class="px-4 py-3">{{ $ticket->equipment?->code_inventaire ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $ticket->intervenant?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $priorityClass }}">
                                {{ ucfirst($ticket->priorite) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->statut)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ optional($ticket->ouvert_le)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-slate-900 hover:underline" href="{{ route('tickets.edit', ['ticketId' => $ticket->id]) }}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                @if ($tickets->isEmpty())
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">Aucun ticket trouvé.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        {{ $tickets->links() }}
    </div>
</div>
