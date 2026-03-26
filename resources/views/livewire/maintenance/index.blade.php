<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Maintenance preventive</h1>
        @can('maintenance.manage')
            <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('maintenance.create') }}">
                Nouveau plan
            </a>
        @endcan
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche equipement, marque, modele..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="status" class="w-full px-3 py-2 border rounded">
                <option value="">Tous statuts</option>
                <option value="actif">Actif</option>
                <option value="suspendu">Suspendu</option>
            </select>
        </div>
        <div>
            <select wire:model.live="due" class="w-full px-3 py-2 border rounded">
                <option value="">Toutes echeances</option>
                <option value="overdue">En retard</option>
                <option value="soon">Sous 14 jours</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Equipement</th>
                    <th class="px-4 py-3 text-left">Structure</th>
                    <th class="px-4 py-3 text-left">Local</th>
                    <th class="px-4 py-3 text-left">Periodicite</th>
                    <th class="px-4 py-3 text-left">Prochaine maintenance</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($plans as $plan)
                    <tr>
                        <td class="px-4 py-3">
                            <div>{{ $plan->equipment?->code_inventaire ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $plan->equipment?->nom ?? ($plan->equipment?->marque.' '.$plan->equipment?->modele) }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $plan->equipment?->structure?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $plan->equipment?->location?->full_label ?? $plan->equipment?->current_location ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $plan->periodicite_jours }} jours</td>
                        <td class="px-4 py-3">{{ optional($plan->prochain)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $plan->statut === 'actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($plan->statut) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @can('maintenance.manage')
                                <a class="text-slate-900 hover:underline" href="{{ route('maintenance.edit', ['planId' => $plan->id]) }}">Modifier</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">Aucun plan de maintenance trouve.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center">
        <div>
            <select wire:model.live="perPage" class="px-3 py-2 border rounded">
                <option value="10">10 / page</option>
                <option value="15">15 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>
        <div>
            {{ $plans->links() }}
        </div>
    </div>
</div>
