<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $equipmentId ? 'Modifier équipement' : 'Nouvel équipement' }}</h1>
        <div class="text-sm text-slate-500">
            Structure: <span class="font-medium text-slate-800">{{ auth()->user()?->structure?->nom ?? 'Non définie' }}</span>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded-lg shadow-sm border">
        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Code inventaire *</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.code_inventaire">
                @error('form.code_inventaire') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" wire:model="form.type_id">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                    @endforeach
                </select>
                @error('form.type_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Catégorie</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" wire:model="form.category_id">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                    @endforeach
                </select>
                @error('form.category_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Marque</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.marque">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Modèle</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.modele">
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Numéro de série</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.numero_serie">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Adresse MAC</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.mac">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Système d'exploitation (OS)</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.os">
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Localisation actuelle (Texte)</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" placeholder="Batiment - Etage - Bureau" wire:model="form.current_location">
                @error('form.current_location') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Local référentiel</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" wire:model="form.location_id">
                    <option value="">-- Sélectionner un local --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->full_label }}</option>
                    @endforeach
                </select>
                @error('form.location_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Statut</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" wire:model="form.statut">
                    <option value="actif">Actif</option>
                    <option value="panne">En panne</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="reforme">Réformé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Salle</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="text" wire:model="form.salle">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Affecté à (Utilisateur)</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" wire:model="form.user_id">
                    <option value="">-- Aucun --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Date d'acquisition</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="date" wire:model="form.date_acquisition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fin de garantie</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" type="date" wire:model="form.garantie_fin">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fournisseur</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" wire:model="form.fournisseur_id">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Notes internes</label>
            <textarea class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" rows="2" wire:model="form.notes"></textarea>
        </div>

        <div class="bg-blue-50 p-4 rounded-md border border-blue-100">
            <label class="block text-sm font-medium text-blue-800 mb-1">Note de changement de localisation</label>
            <textarea class="w-full px-3 py-2 border border-blue-200 rounded-md focus:ring-2 focus:ring-blue-500 outline-none" rows="2" wire:model="form.locationChangeNote" placeholder="Raison du déplacement (optionnel)"></textarea>
            @error('form.locationChangeNote') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($form->translations as $locale => $fields)
                <div class="p-4 border rounded-md bg-slate-50">
                    <div class="text-sm font-bold text-slate-600 mb-3 border-b pb-2">LANGUE: {{ strtoupper($locale) }}</div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase">Nom *</label>
                            <input class="w-full px-2 py-1.5 border rounded-md text-sm" type="text" wire:model="form.translations.{{ $locale }}.nom">
                            @error("form.translations.$locale.nom") <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase">Description</label>
                            <textarea class="w-full px-2 py-1.5 border rounded-md text-sm" rows="2" wire:model="form.translations.{{ $locale }}.description"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase">Localisation (Traduit)</label>
                            <input class="w-full px-2 py-1.5 border rounded-md text-sm" type="text" wire:model="form.translations.{{ $locale }}.localisation">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex items-center gap-3 pt-4 border-t">
            <button class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm transition-colors" type="submit">
                Enregistrer l'équipement
            </button>
            <a class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 rounded-md transition-colors" href="{{ route('equipments.index') }}">Annuler</a>
        </div>
    </form>

    @if ($equipmentId)
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b bg-slate-50">
                <h2 class="font-bold text-slate-800">Historique des localisations</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Ancienne localisation</th>
                            <th class="px-6 py-3 text-left">Nouvelle localisation</th>
                            <th class="px-6 py-3 text-left">Modifié par</th>
                            <th class="px-6 py-3 text-left">Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($locationHistories as $history)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium">
                                    {{ $history->changed_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 italic">
                                    {{ $history->previous_location ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-900 font-medium">
                                    {{ $history->new_location ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-700">
                                            {{ substr($history->user?->name ?? 'S', 0, 1) }}
                                        </div>
                                        {{ $history->user?->name ?? 'Système' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate">
                                    {{ $history->note ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Aucun historique de localisation pour cet équipement.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
