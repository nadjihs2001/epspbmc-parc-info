<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $assignmentId ? 'Modifier affectation' : 'Nouvelle affectation' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Équipement</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.equipment_id">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($equipments as $equipment)
                        <option value="{{ $equipment->id }}">
                            {{ $equipment->code_inventaire }}{{ $equipment->nom ? ' - '.$equipment->nom : '' }}
                        </option>
                    @endforeach
                </select>
                @error('form.equipment_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Utilisateur</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.user_id">
                    <option value="">-- Non assigné --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('form.user_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Date début</label>
                <input class="w-full px-3 py-2 border rounded" type="date" wire:model.defer="form.date_debut">
            </div>
            <div>
                <label class="text-sm font-medium">Date fin</label>
                <input class="w-full px-3 py-2 border rounded" type="date" wire:model.defer="form.date_fin">
                @error('form.date_fin') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Local cible</label>
            <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.location_id">
                <option value="">-- Non precise --</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->full_label }}</option>
                @endforeach
            </select>
            @error('form.location_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Justification</label>
            <textarea class="w-full px-3 py-2 border rounded" wire:model.defer="form.justification"></textarea>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('assignments.index') }}">Annuler</a>
        </div>
    </form>
</div>
