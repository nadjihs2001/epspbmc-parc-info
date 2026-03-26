<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Équipements</h1>
        <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('equipments.create') }}">
            Nouvel équipement
        </a>
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche code, nom, marque, modèle..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="status" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="panne">En panne</option>
                <option value="maintenance">Maintenance</option>
                <option value="reforme">Réformé</option>
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
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Localisation</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($equipments as $equipment)
                    @php
                        $statusClass = match ($equipment->statut) {
                            'actif' => 'bg-emerald-100 text-emerald-700',
                            'maintenance' => 'bg-amber-100 text-amber-700',
                            'panne' => 'bg-rose-100 text-rose-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $equipment->code_inventaire }}</td>
                        <td class="px-4 py-3">{{ $equipment->nom }}</td>
                        <td class="px-4 py-3">{{ $equipment->location?->full_label ?? $equipment->current_location ?? $equipment->salle ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $equipment->type?->nom }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                {{ ucfirst($equipment->statut) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-slate-900 hover:underline" href="{{ route('equipments.edit', ['equipmentId' => $equipment->id]) }}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                @if ($equipments->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucun équipement trouvé.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        {{ $equipments->links() }}
    </div>
</div>
