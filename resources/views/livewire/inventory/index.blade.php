<div class="space-y-4">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-xl font-semibold">Inventaire</h1>
            <p class="text-sm text-slate-500">Vue consolidée du parc informatique par structure, type et statut.</p>
        </div>
        <div class="flex gap-2">
            @can('inventory.export')
                <a
                    class="px-4 py-2 text-sm font-semibold border rounded"
                    href="{{ route('inventory.export.csv', request()->query()) }}"
                >
                    Export CSV
                </a>
            @endcan
            <a
                class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded"
                href="{{ route('inventory.print', request()->query()) }}"
                target="_blank"
            >
                Version imprimable
            </a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="p-5 bg-white rounded shadow">
            <div class="text-sm text-slate-500">Total</div>
            <div class="text-3xl font-semibold mt-1">{{ $summary['total'] }}</div>
        </div>
        <div class="p-5 bg-white rounded shadow">
            <div class="text-sm text-slate-500">Actifs</div>
            <div class="text-3xl font-semibold mt-1 text-emerald-600">{{ $summary['actif'] }}</div>
        </div>
        <div class="p-5 bg-white rounded shadow">
            <div class="text-sm text-slate-500">En panne</div>
            <div class="text-3xl font-semibold mt-1 text-rose-600">{{ $summary['panne'] }}</div>
        </div>
        <div class="p-5 bg-white rounded shadow">
            <div class="text-sm text-slate-500">Maintenance</div>
            <div class="text-3xl font-semibold mt-1 text-amber-600">{{ $summary['maintenance'] }}</div>
        </div>
        <div class="p-5 bg-white rounded shadow">
            <div class="text-sm text-slate-500">Réformés</div>
            <div class="text-3xl font-semibold mt-1 text-slate-600">{{ $summary['reforme'] }}</div>
        </div>
    </div>

    <div class="grid gap-3 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche code, nom, localisation, série..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="structure_id" class="w-full px-3 py-2 border rounded" @disabled(auth()->user()?->structure_id && !auth()->user()?->hasRole('Super Admin'))>
                <option value="">Toutes les structures</option>
                @foreach ($structures as $structure)
                    <option value="{{ $structure->id }}">{{ $structure->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="type_id" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les types</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}">{{ $type->nom ?? ('Type #'.$type->id) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="statut" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="panne">En panne</option>
                <option value="maintenance">Maintenance</option>
                <option value="reforme">Réformé</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Structure</th>
                    <th class="px-4 py-3 text-left">Local</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($equipments as $equipment)
                    <tr>
                        <td class="px-4 py-3">{{ $equipment->code_inventaire }}</td>
                        <td class="px-4 py-3">{{ $equipment->nom }}</td>
                        <td class="px-4 py-3">{{ $equipment->type?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $equipment->structure?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $equipment->location?->full_label ?? $equipment->current_location ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                @class([
                                    'bg-emerald-100 text-emerald-700' => $equipment->statut === 'actif',
                                    'bg-rose-100 text-rose-700' => $equipment->statut === 'panne',
                                    'bg-amber-100 text-amber-700' => $equipment->statut === 'maintenance',
                                    'bg-slate-100 text-slate-700' => $equipment->statut === 'reforme',
                                ])">
                                {{ ucfirst($equipment->statut) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucune ligne d'inventaire trouvee.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center">
        <div>
            <select wire:model.live="perPage" class="px-3 py-2 border rounded">
                <option value="10">10 / page</option>
                <option value="20">20 / page</option>
                <option value="50">50 / page</option>
                <option value="100">100 / page</option>
            </select>
        </div>
        <div>
            {{ $equipments->links() }}
        </div>
    </div>
</div>
