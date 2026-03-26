<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $planId ? 'Modifier plan de maintenance' : 'Nouveau plan de maintenance' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="text-sm font-medium">Equipement</label>
            <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.equipment_id">
                <option value="">Selectionner un equipement</option>
                @foreach ($equipments as $equipment)
                    <option value="{{ $equipment->id }}">
                        {{ $equipment->code_inventaire }}{{ $equipment->nom ? ' - '.$equipment->nom : '' }}
                    </option>
                @endforeach
            </select>
            @error('form.equipment_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Periodicite (jours)</label>
                <input class="w-full px-3 py-2 border rounded" type="number" min="1" wire:model.defer="form.periodicite_jours">
                @error('form.periodicite_jours') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Prochaine maintenance</label>
                <input class="w-full px-3 py-2 border rounded" type="date" wire:model.defer="form.prochain">
                @error('form.prochain') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Statut</label>
            <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.statut">
                <option value="actif">Actif</option>
                <option value="suspendu">Suspendu</option>
            </select>
            @error('form.statut') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('maintenance.index') }}">Annuler</a>
        </div>
    </form>
</div>
