<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Affectations</h1>
        <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('assignments.create') }}">
            Nouvelle affectation
        </a>
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche équipement, utilisateur, justification..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="actif" class="w-full px-3 py-2 border rounded">
                <option value="">Tous états</option>
                <option value="1">Actives</option>
                <option value="0">Terminées</option>
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
                    <th class="px-4 py-3 text-left">Équipement</th>
                    <th class="px-4 py-3 text-left">Utilisateur</th>
                    <th class="px-4 py-3 text-left">Début</th>
                    <th class="px-4 py-3 text-left">Fin</th>
                    <th class="px-4 py-3 text-left">État</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($assignments as $assignment)
                    <tr>
                        <td class="px-4 py-3">{{ $assignment->equipment?->code_inventaire }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $assignment->user?->name ?? 'Aucun utilisateur' }}</div>
                            <div class="text-xs text-slate-500">{{ $assignment->location?->full_label ?? 'Local non precise' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ optional($assignment->date_debut)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ optional($assignment->date_fin)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            @if ($assignment->actif)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-emerald-100 text-emerald-700">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-slate-100 text-slate-700">Terminée</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-slate-900 hover:underline" href="{{ route('assignments.edit', ['assignmentId' => $assignment->id]) }}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                @if ($assignments->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucune affectation trouvée.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        {{ $assignments->links() }}
    </div>
</div>
