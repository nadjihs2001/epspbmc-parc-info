<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Intervenants</h1>
        <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('intervenants.create') }}">
            Nouvel intervenant
        </a>
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche nom, email, téléphone, spécialité..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="status" class="w-full px-3 py-2 border rounded">
                <option value="">Tous états</option>
                <option value="1">Actifs</option>
                <option value="0">Inactifs</option>
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
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Téléphone</th>
                    <th class="px-4 py-3 text-left">Spécialité</th>
                    <th class="px-4 py-3 text-left">État</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($intervenants as $intervenant)
                    <tr>
                        <td class="px-4 py-3">{{ $intervenant->nom }}</td>
                        <td class="px-4 py-3">{{ $intervenant->email ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $intervenant->telephone ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $intervenant->specialite ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($intervenant->actif)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-emerald-100 text-emerald-700">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-slate-100 text-slate-700">Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-slate-900 hover:underline mr-3" href="{{ route('intervenants.edit', ['intervenantId' => $intervenant->id]) }}">Modifier</a>
                            <button
                                type="button"
                                wire:click="delete({{ $intervenant->id }})"
                                wire:confirm="Supprimer cet intervenant ?"
                                class="text-rose-600 hover:underline"
                            >
                                Supprimer
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if ($intervenants->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucun intervenant trouvé.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        {{ $intervenants->links() }}
    </div>
</div>
