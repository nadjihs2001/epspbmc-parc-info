<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $structureId ? 'Modifier structure' : 'Nouvelle structure' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Nom</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.nom">
                @error('form.nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Type</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.type">
                    <option value="epsp">EPSP</option>
                    <option value="siege">Siege</option>
                    <option value="direction">Direction</option>
                    <option value="service">Service</option>
                    <option value="polyclinique">Polyclinique</option>
                    <option value="centre_sante">Centre de sante</option>
                    <option value="pharmacie">Pharmacie</option>
                    <option value="magasin">Magasin</option>
                    <option value="autre">Autre</option>
                </select>
                @error('form.type') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Code</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.code">
                @error('form.code') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Parent</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.parent_id">
                    <option value="">Aucun</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->nom }}</option>
                    @endforeach
                </select>
                @error('form.parent_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Responsable</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.responsable_nom">
                @error('form.responsable_nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" wire:model.defer="form.actif">
                    <span>Structure active</span>
                </label>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Adresse</label>
            <textarea class="w-full px-3 py-2 border rounded" rows="3" wire:model.defer="form.adresse"></textarea>
            @error('form.adresse') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('structures.index') }}">Annuler</a>
        </div>
    </form>
</div>
