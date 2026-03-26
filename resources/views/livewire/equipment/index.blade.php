<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Équipements</h1>
            <p class="text-sm text-slate-500">Gestion de l'inventaire matériel et technique.</p>
        </div>
        <div class="flex items-center gap-3">
            <a class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm transition-colors" href="{{ route('equipments.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nouvel équipement
            </a>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border space-y-4">
        <div class="grid gap-4 md:grid-cols-12">
            <div class="md:col-span-5">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Recherche</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Code, nom, marque, modèle, localisation..."
                        class="w-full pl-10 pr-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Statut</label>
                <select wire:model.live="status" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="actif">Actif</option>
                    <option value="panne">En panne</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="reforme">Réformé</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Catégorie</label>
                <select wire:model.live="category" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <option value="">Toutes</option>
                    @foreach(\App\Models\EquipmentCategory::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Affichage</label>
                <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Équipement</th>
                        <th class="px-6 py-4 text-left">Technique</th>
                        <th class="px-6 py-4 text-left">Localisation</th>
                        <th class="px-6 py-4 text-left">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach ($equipments as $equipment)
                        @php
                            $statusColors = match ($equipment->statut) {
                                'actif' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'maintenance' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'panne' => 'bg-rose-50 text-rose-700 border-rose-100',
                                default => 'bg-slate-50 text-slate-700 border-slate-100',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 border">
                                        @if(str_contains(strtolower($equipment->type?->nom), 'ordinateur'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        @elseif(str_contains(strtolower($equipment->type?->nom), 'imprimante'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $equipment->nom }}</div>
                                        <div class="text-xs font-mono text-slate-500">{{ $equipment->code_inventaire }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1 text-xs">
                                    <div class="flex items-center text-slate-700">
                                        <span class="w-12 text-slate-400 font-semibold uppercase">Type:</span>
                                        <span>{{ $equipment->type?->nom }}</span>
                                    </div>
                                    <div class="flex items-center text-slate-700">
                                        <span class="w-12 text-slate-400 font-semibold uppercase">Marque:</span>
                                        <span>{{ $equipment->marque }} {{ $equipment->modele }}</span>
                                    </div>
                                    @if($equipment->numero_serie)
                                        <div class="flex items-center text-slate-700">
                                            <span class="w-12 text-slate-400 font-semibold uppercase">S/N:</span>
                                            <span class="font-mono">{{ $equipment->numero_serie }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-medium">{{ $equipment->location?->full_label ?? $equipment->current_location ?? $equipment->salle ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $equipment->structure?->nom }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusColors }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 @if($equipment->statut === 'actif') bg-emerald-500 @elseif($equipment->statut === 'panne') bg-rose-500 @else bg-amber-500 @endif"></span>
                                    {{ ucfirst($equipment->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" href="{{ route('equipments.edit', ['equipmentId' => $equipment->id]) }}" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($equipments->isEmpty())
            <div class="px-6 py-12 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <p class="text-lg font-medium">Aucun équipement trouvé</p>
                <p class="text-sm">Essayez d'ajuster vos filtres de recherche.</p>
            </div>
        @endif
    </div>

    <div class="mt-4">
        {{ $equipments->links() }}
    </div>
</div>
