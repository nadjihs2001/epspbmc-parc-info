<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $intervenantId ? 'Modifier intervenant' : 'Nouvel intervenant' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Nom</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.nom">
                @error('form.nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Email</label>
                <input class="w-full px-3 py-2 border rounded" type="email" wire:model.defer="form.email">
                @error('form.email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Téléphone</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.telephone">
                @error('form.telephone') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Spécialité</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.specialite">
                @error('form.specialite') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Structure</label>
                <input class="w-full px-3 py-2 border rounded bg-slate-100" type="text" value="{{ auth()->user()?->structure_id }}" disabled>
            </div>
            <div>
                <label class="text-sm font-medium">État</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.actif">
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
                @error('form.actif') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('intervenants.index') }}">Annuler</a>
        </div>
    </form>
</div>
