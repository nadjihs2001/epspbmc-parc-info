<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $ticketId ? 'Modifier ticket' : 'Nouveau ticket' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Code</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.code">
                @error('form.code') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Type</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.type">
                    <option value="panne">Panne</option>
                    <option value="maintenance_preventive">Maintenance preventive</option>
                </select>
                @error('form.type') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Priorité</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.priorite">
                    <option value="basse">Basse</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                    <option value="critique">Critique</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium">Intervenant maintenance</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.intervenant_id">
                    <option value="">-- Non affecté --</option>
                    @foreach ($intervenants as $intervenant)
                        <option value="{{ $intervenant->id }}">{{ $intervenant->nom }}</option>
                    @endforeach
                </select>
                @error('form.intervenant_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Statut</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.statut">
                    <option value="ouvert">Ouvert</option>
                    <option value="en_cours">En cours</option>
                    <option value="en_attente">En attente</option>
                    <option value="resolu">Résolu</option>
                    <option value="clos">Clos</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Description</label>
            <textarea class="w-full px-3 py-2 border rounded" wire:model.defer="form.description"></textarea>
            @error('form.description') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('tickets.index') }}">Annuler</a>
        </div>
    </form>
</div>
