<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $licenseId ? 'Modifier licence' : 'Nouvelle licence' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Nom</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.nom">
                @error('form.nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Type</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.type">
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Clé</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.cle">
            </div>
            <div>
                <label class="text-sm font-medium">Expiration</label>
                <input class="w-full px-3 py-2 border rounded" type="date" wire:model.defer="form.expiration">
            </div>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('licenses.index') }}">Annuler</a>
        </div>
    </form>
</div>
