<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Utilisateurs</h1>
        @can('users.create')
            <a class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" href="{{ route('users.create') }}">
                Nouvel utilisateur
            </a>
        @endcan
    </div>

    <div class="grid gap-3 md:grid-cols-4">
        <div class="md:col-span-2">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Recherche nom, email, fonction..."
                class="w-full px-3 py-2 border rounded"
            >
        </div>
        <div>
            <select wire:model.live="role" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="status" class="w-full px-3 py-2 border rounded">
                <option value="">Tous etats</option>
                <option value="1">Actifs</option>
                <option value="0">Inactifs</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Structure</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Etat</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->fonction ?? 'Fonction non precisee' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $user->structure?->nom ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $user->department?->nom ?? 'Sans service' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($user->actif)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-emerald-100 text-emerald-700">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-slate-100 text-slate-700">Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @can('users.update')
                                <a class="text-slate-900 hover:underline mr-3" href="{{ route('users.edit', ['userId' => $user->id]) }}">Modifier</a>
                            @endcan
                            @can('users.delete')
                                @if (auth()->id() !== $user->id)
                                    <button
                                        type="button"
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="Supprimer cet utilisateur ?"
                                        class="text-rose-600 hover:underline"
                                    >
                                        Supprimer
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucun utilisateur trouve.</td>
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
            {{ $users->links() }}
        </div>
    </div>
</div>
