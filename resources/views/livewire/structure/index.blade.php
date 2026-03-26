<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Structures</h1>
        @can('structures.create')
            <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('structures.create') }}">
                Nouvelle structure
            </a>
        @endcan
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche nom, code, responsable..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="type" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les types</option>
                <option value="epsp">EPSP</option>
                <option value="siege">Siege</option>
                <option value="direction">Direction</option>
                <option value="service">Service</option>
                <option value="polyclinique">Polyclinique</option>
                <option value="centre_sante">Centre de sante</option>
                <option value="pharmacie">Pharmacie</option>
                <option value="magasin">Magasin</option>
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
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Parent</th>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-left">Responsable</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($structures as $structure)
                    <tr>
                        <td class="px-4 py-3">{{ $structure->nom }}</td>
                        <td class="px-4 py-3">{{ str_replace('_', ' ', ucfirst($structure->type)) }}</td>
                        <td class="px-4 py-3">{{ $structure->parent?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $structure->code ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $structure->responsable_nom ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            @can('structures.update')
                                <a class="text-slate-900 hover:underline" href="{{ route('structures.edit', ['structureId' => $structure->id]) }}">Modifier</a>
                            @else
                                <span class="text-slate-400">Lecture seule</span>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucune structure trouvee.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $structures->links() }}
    </div>
</div>
