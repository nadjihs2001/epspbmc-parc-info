<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $locationId ? 'Modifier local' : 'Nouveau local' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Structure</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.live="form.structure_id">
                    <option value="">Selectionner</option>
                    @foreach ($structures as $structure)
                        <option value="{{ $structure->id }}">{{ $structure->nom }}</option>
                    @endforeach
                </select>
                @error('form.structure_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Service / entite</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.department_id">
                    <option value="">Aucun</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->nom }}</option>
                    @endforeach
                </select>
                @error('form.department_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Nom</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.nom">
                @error('form.nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Type</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.type">
                    <option value="bureau">Bureau</option>
                    <option value="secretariat">Secretariat</option>
                    <option value="numerisation">Salle de numerisation</option>
                    <option value="local_medical">Local medical</option>
                    <option value="stockage">Stockage</option>
                    <option value="bureau_technique">Bureau technique</option>
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
                <label class="text-sm font-medium">Etage</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.etage">
                @error('form.etage') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Local parent</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.parent_id">
                    <option value="">Aucun</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->nom }}</option>
                    @endforeach
                </select>
                @error('form.parent_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" wire:model.defer="form.actif">
                    <span>Local actif</span>
                </label>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Details</label>
            <textarea class="w-full px-3 py-2 border rounded" rows="3" wire:model.defer="form.details"></textarea>
            @error('form.details') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('locations.index') }}">Annuler</a>
        </div>
    </form>
</div>
