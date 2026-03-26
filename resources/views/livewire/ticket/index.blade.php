<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tickets d'Assistance</h1>
            <p class="text-sm text-slate-500">Suivi des interventions techniques et pannes.</p>
        </div>
        <div class="flex items-center gap-3">
            <a class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm transition-colors" href="{{ route('tickets.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nouveau ticket
            </a>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border space-y-4">
        <div class="grid gap-4 md:grid-cols-12">
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Recherche</label>
                <div class="relative text-sm">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Code, description, équipement..."
                        class="w-full pl-10 pr-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none"
                    >
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Type</label>
                <select wire:model.live="type" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <option value="">Tous</option>
                    <option value="panne">Panne</option>
                    <option value="maintenance_preventive">Maint. Prév.</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Statut</label>
                <select wire:model.live="status" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <option value="">Tous</option>
                    <option value="ouvert">Ouvert</option>
                    <option value="en_cours">En cours</option>
                    <option value="en_attente">En attente</option>
                    <option value="resolu">Résolu</option>
                    <option value="clos">Clos</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Priorité</label>
                <select wire:model.live="priority" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <option value="">Toutes</option>
                    <option value="basse">Basse</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                    <option value="critique">Critique</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Afficher</label>
                <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <option value="15">15 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Ticket</th>
                        <th class="px-6 py-4 text-left">Détails</th>
                        <th class="px-6 py-4 text-left">Priorité</th>
                        <th class="px-6 py-4 text-left">Statut</th>
                        <th class="px-6 py-4 text-left">Dates</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach ($tickets as $ticket)
                        @php
                            $priorityColors = match ($ticket->priorite) {
                                'critique' => 'bg-rose-50 text-rose-700 border-rose-100',
                                'haute' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'moyenne' => 'bg-sky-50 text-sky-700 border-sky-100',
                                default => 'bg-slate-50 text-slate-700 border-slate-100',
                            };

                            $statusColors = match ($ticket->statut) {
                                'resolu' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'clos' => 'bg-slate-50 text-slate-700 border-slate-100',
                                'en_cours' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'en_attente' => 'bg-amber-50 text-amber-700 border-amber-100',
                                default => 'bg-rose-50 text-rose-700 border-rose-100',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 @if($ticket->type === 'panne') bg-rose-50 text-rose-600 @else bg-indigo-50 text-indigo-600 @endif rounded-full border border-current opacity-30">
                                        @if($ticket->type === 'panne')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 font-mono">{{ $ticket->code }}</div>
                                        <div class="text-xs text-slate-500 uppercase">{{ $ticket->type === 'maintenance_preventive' ? 'Maint. Prév.' : 'Panne' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs overflow-hidden">
                                    <div class="truncate text-slate-900 font-medium">{{ $ticket->description }}</div>
                                    <div class="text-xs text-slate-500">
                                        Équipement: <span class="font-bold text-slate-700">{{ $ticket->equipment?->code_inventaire ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold border {{ $priorityColors }}">
                                    @if($ticket->priorite === 'critique') 🔴 @elseif($ticket->priorite === 'haute') 🟠 @elseif($ticket->priorite === 'moyenne') 🟢 @else 🔵 @endif
                                    {{ ucfirst($ticket->priorite) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold border {{ $statusColors }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs space-y-1">
                                    <div class="flex items-center text-slate-500">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ optional($ticket->ouvert_le)->format('d/m H:i') ?? 'N/A' }}
                                    </div>
                                    <div class="text-slate-400">
                                        Par: {{ $ticket->user?->name ?? 'Système' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" href="{{ route('tickets.edit', ['ticketId' => $ticket->id]) }}" title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($tickets->isEmpty())
            <div class="px-6 py-12 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                <p class="text-lg font-medium">Aucun ticket trouvé</p>
            </div>
        @endif
    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
