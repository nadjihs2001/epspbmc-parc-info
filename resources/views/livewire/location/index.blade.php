<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Locaux</h1>
        @can('locations.create')
            <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('locations.create') }}">
                Nouveau local
            </a>
        @endcan
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche nom, structure, code..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="type" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les types</option>
                <option value="bureau">Bureau</option>
                <option value="secretariat">Secretariat</option>
                <option value="numerisation">Numerisation</option>
                <option value="local_medical">Local medical</option>
                <option value="stockage">Stockage</option>
                <option value="bureau_technique">Bureau technique</option>
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
                    <th class="px-4 py-3 text-left">Structure</th>
                    <th class="px-4 py-3 text-left">Service</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($locations as $location)
                    <tr>
                        <td class="px-4 py-3">{{ $location->nom }}</td>
                        <td class="px-4 py-3">{{ $location->structure?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $location->department?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ str_replace('_', ' ', ucfirst($location->type)) }}</td>
                        <td class="px-4 py-3">{{ $location->code ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            @can('locations.update')
                                <a class="text-slate-900 hover:underline" href="{{ route('locations.edit', ['locationId' => $location->id]) }}">Modifier</a>
                            @else
                                <span class="text-slate-400">Lecture seule</span>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucun local trouve.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $locations->links() }}
    </div>
</div>
