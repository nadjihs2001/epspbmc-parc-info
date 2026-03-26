<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $equipmentId ? 'Modifier équipement' : 'Nouvel équipement' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Code inventaire</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.code_inventaire">
                @error('form.code_inventaire') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Type</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.type_id">
                    <option value="">--</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                    @endforeach
                </select>
                @error('form.type_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Marque</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.marque">
            </div>
            <div>
                <label class="text-sm font-medium">Modèle</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.modele">
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Localisation actuelle</label>
                <input class="w-full px-3 py-2 border rounded" type="text" placeholder="Batiment - Etage - Bureau" wire:model.defer="form.current_location">
                @error('form.current_location') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Local reference</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.location_id">
                    <option value="">-- Selectionner un local --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->full_label }}</option>
                    @endforeach
                </select>
                @error('form.location_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Statut</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.statut">
                    <option value="actif">Actif</option>
                    <option value="panne">En panne</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="reforme">Réformé</option>
                </select>
            </div>
        </div>

            <div>
                <label class="text-sm font-medium">Salle</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.salle">
            </div>
            <div>
                <label class="text-sm font-medium">Structure</label>
                <input class="w-full px-3 py-2 border rounded bg-slate-100" type="text" value="{{ auth()->user()?->structure?->nom ?? 'Non definie' }}" disabled>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Note de changement de localisation</label>
            <textarea class="w-full px-3 py-2 border rounded" rows="2" wire:model.defer="locationChangeNote" placeholder="Raison du déplacement (optionnel)"></textarea>
            @error('locationChangeNote') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($translations as $locale => $fields)
                <div class="p-3 border rounded">
                    <div class="text-sm font-semibold mb-2">Langue: {{ strtoupper($locale) }}</div>
                    <label class="text-sm">Nom</label>
                    <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="translations.{{ $locale }}.nom">
                    @error("translations.$locale.nom") <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    <label class="text-sm mt-2 block">Description</label>
                    <textarea class="w-full px-3 py-2 border rounded" wire:model.defer="translations.{{ $locale }}.description"></textarea>
                    <label class="text-sm mt-2 block">Localisation</label>
                    <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="translations.{{ $locale }}.localisation">
                </div>
            @endforeach
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('equipments.index') }}">Annuler</a>
        </div>
    </form>

    @if ($equipmentId)
        <div class="overflow-x-auto bg-white rounded shadow">
            <div class="px-4 py-3 border-b">
                <h2 class="font-semibold text-slate-800">Historique des localisations</h2>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Ancienne localisation</th>
                        <th class="px-4 py-3 text-left">Nouvelle localisation</th>
                        <th class="px-4 py-3 text-left">Modifié par</th>
                        <th class="px-4 py-3 text-left">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($locationHistories as $history)
                        <tr>
                            <td class="px-4 py-3">{{ optional($history->changed_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">{{ $history->previous_location ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $history->new_location ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $history->user?->name ?? 'Système' }}</td>
                            <td class="px-4 py-3">{{ $history->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun historique de localisation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
