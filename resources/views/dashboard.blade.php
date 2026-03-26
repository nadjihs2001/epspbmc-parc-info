<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5 mb-6">
            <div class="p-5 bg-white rounded shadow relative overflow-hidden">
                <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-indigo-100"></div>
                <div class="text-sm text-slate-500">Équipements</div>
                <div class="text-3xl font-semibold mt-1">{{ $stats['equipments'] ?? 0 }}</div>
            </div>
            <div class="p-5 bg-white rounded shadow relative overflow-hidden">
                <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-sky-100"></div>
                <div class="text-sm text-slate-500">Interventions en cours</div>
                <div class="text-3xl font-semibold mt-1">{{ $stats['tickets_open'] ?? 0 }}</div>
            </div>
            <div class="p-5 bg-white rounded shadow relative overflow-hidden">
                <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-rose-100"></div>
                <div class="text-sm text-slate-500">Alertes</div>
                <div class="text-3xl font-semibold mt-1">{{ $stats['alerts'] ?? 0 }}</div>
            </div>
            <div class="p-5 bg-white rounded shadow relative overflow-hidden">
                <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-emerald-100"></div>
                <div class="text-sm text-slate-500">Locaux suivis</div>
                <div class="text-3xl font-semibold mt-1">{{ $stats['locations'] ?? 0 }}</div>
            </div>
            <div class="p-5 bg-white rounded shadow relative overflow-hidden">
                <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-amber-100"></div>
                <div class="text-sm text-slate-500">Structures suivies</div>
                <div class="text-3xl font-semibold mt-1">{{ $stats['structures'] ?? 0 }}</div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
            <div class="p-4 bg-white rounded shadow space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Tickets urgents</h3>
                    <a class="text-sm text-slate-600 hover:underline" href="{{ route('tickets.index') }}">Voir tout</a>
                </div>
                <div class="space-y-2">
                    @forelse ($urgentTickets as $ticket)
                        <a href="{{ route('tickets.edit', ['ticketId' => $ticket->id]) }}" class="block p-2 rounded border hover:bg-slate-50">
                            <div class="text-sm font-semibold text-slate-800">{{ $ticket->code }}</div>
                            <div class="text-xs text-slate-600">{{ ucfirst($ticket->priorite) }} - {{ ucfirst(str_replace('_', ' ', $ticket->statut)) }}</div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Aucun ticket urgent.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-white rounded shadow space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Licences à renouveler</h3>
                    <a class="text-sm text-slate-600 hover:underline" href="{{ route('licenses.index') }}">Voir tout</a>
                </div>
                <div class="space-y-2">
                    @forelse ($licensesExpiringSoon as $license)
                        <a href="{{ route('licenses.edit', ['licenseId' => $license->id]) }}" class="block p-2 rounded border hover:bg-slate-50">
                            <div class="text-sm font-semibold text-slate-800">{{ $license->nom }}</div>
                            <div class="text-xs text-slate-600">Expiration: {{ optional($license->expiration)->format('Y-m-d') }}</div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Aucune licence à renouveler sous 30 jours.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-white rounded shadow space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Maintenance à venir</h3>
                    <a class="text-sm text-slate-600 hover:underline" href="{{ route('maintenance.index') }}">Voir tout</a>
                </div>
                <div class="space-y-2">
                    @forelse ($upcomingMaintenance as $plan)
                        <a href="{{ route('maintenance.edit', ['planId' => $plan->id]) }}" class="block p-2 rounded border hover:bg-slate-50">
                            <div class="text-sm font-semibold text-slate-800">{{ $plan->equipment?->code_inventaire ?? 'Équipement' }}</div>
                            <div class="text-xs text-slate-600">Prévu le {{ optional($plan->prochain)->format('Y-m-d') ?? '-' }}</div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Aucune maintenance planifiée.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-white rounded shadow space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Répartition du parc</h3>
                    <span class="text-sm text-slate-600">Top structures</span>
                </div>
                <div class="space-y-3">
                    @forelse ($equipmentByStructure as $item)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ $item->structure?->nom ?? 'Structure inconnue' }}</span>
                                <span class="text-slate-500">{{ $item->total }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div
                                    class="h-2 rounded-full bg-indigo-500"
                                    style="width: {{ max(12, min(100, (int) (($item->total / max(1, $stats['equipments'])) * 100))) }}%;"
                                ></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucune donnee de repartition disponible.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-white rounded shadow space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Alertes non résolues</h3>
                    <span class="text-sm text-slate-600">Top 5</span>
                </div>
                <div class="space-y-2">
                    @forelse ($unresolvedAlerts as $alert)
                        <div class="p-2 rounded border">
                            <div class="text-sm font-semibold text-slate-800">{{ ucfirst($alert->priorite) }} - {{ $alert->type }}</div>
                            <div class="text-xs text-slate-600">{{ $alert->message }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucune alerte non résolue.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
