<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Réseau - Plages IP</h1>
        <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('network.ranges.create') }}">
            Nouvelle plage
        </a>
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche CIDR, passerelle, DNS..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="vlan" class="w-full px-3 py-2 border rounded">
                <option value="">Tous VLAN</option>
                <option value="with">Avec VLAN</option>
                <option value="without">Sans VLAN</option>
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
                    <th class="px-4 py-3 text-left">CIDR</th>
                    <th class="px-4 py-3 text-left">Passerelle</th>
                    <th class="px-4 py-3 text-left">VLAN</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($ranges as $range)
                    <tr>
                        <td class="px-4 py-3">{{ $range->cidr }}</td>
                        <td class="px-4 py-3">{{ $range->passerelle }}</td>
                        <td class="px-4 py-3">{{ $range->vlan?->nom ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-slate-900 hover:underline" href="{{ route('network.ranges.edit', ['rangeId' => $range->id]) }}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                @if ($ranges->isEmpty())
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">Aucune plage IP trouvée.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div>
        {{ $ranges->links() }}
    </div>
</div>
