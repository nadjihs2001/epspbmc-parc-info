<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Licences</h1>
        <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('licenses.create') }}">
            Nouvelle licence
        </a>
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche nom, type, clé..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="expiration" class="w-full px-3 py-2 border rounded">
                <option value="">Toutes expirations</option>
                <option value="expired">Expirées</option>
                <option value="soon">Expire sous 30 jours</option>
                <option value="none">Sans date</option>
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
                    <th class="px-4 py-3 text-left">Expiration</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($licenses as $license)
                    <tr>
                        <td class="px-4 py-3">{{ $license->nom }}</td>
                        <td class="px-4 py-3">{{ $license->type }}</td>
                        <td class="px-4 py-3">{{ optional($license->expiration)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-slate-900 hover:underline" href="{{ route('licenses.edit', ['licenseId' => $license->id]) }}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                @if ($licenses->isEmpty())
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">Aucune licence trouvée.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        {{ $licenses->links() }}
    </div>
</div>
