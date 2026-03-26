<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $ticketId ? 'Modifier ticket' : 'Nouveau ticket' }}</h1>
        <div class="text-sm text-slate-500">
            Structure: <span class="font-medium text-slate-800">{{ auth()->user()?->structure?->nom ?? 'Non définie' }}</span>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded-lg shadow-sm border">
        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Code ticket *</label>
                <input class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" type="text" wire:model="form.code">
                @error('form.code') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.type">
                    <option value="panne">🔧 Panne</option>
                    <option value="maintenance_preventive">📅 Maintenance préventive</option>
                </select>
                @error('form.type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Priorité *</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.priorite">
                    <option value="basse">🔵 Basse</option>
                    <option value="moyenne">🟢 Moyenne</option>
                    <option value="haute">🟠 Haute</option>
                    <option value="critique">🔴 Critique</option>
                </select>
                @error('form.priorite') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Équipement concerné</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.equipment_id">
                    <option value="">-- Sélectionner l'équipement --</option>
                    @foreach ($equipments as $equipment)
                        <option value="{{ $equipment->id }}">{{ $equipment->code_inventaire }} - {{ $equipment->nom }} ({{ $equipment->marque }} {{ $equipment->modele }})</option>
                    @endforeach
                </select>
                @error('form.equipment_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Intervenant affecté</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.intervenant_id">
                    <option value="">-- Non affecté --</option>
                    @foreach ($intervenants as $intervenant)
                        <option value="{{ $intervenant->id }}">{{ $intervenant->nom }} ({{ $intervenant->specialite }})</option>
                    @endforeach
                </select>
                @error('form.intervenant_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Statut actuel *</label>
                <select class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.statut">
                    <option value="ouvert">Ouvert</option>
                    <option value="en_cours">En cours</option>
                    <option value="en_attente">En attente</option>
                    <option value="resolu">Résolu</option>
                    <option value="clos">Clos</option>
                </select>
                @error('form.statut') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                 <label class="block text-sm font-medium text-slate-700 mb-1">Créé par</label>
                 <input class="w-full px-3 py-2 border rounded-md bg-slate-50 text-slate-500" type="text" value="{{ auth()->user()?->name }}" disabled>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Description du problème *</label>
            <textarea class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" rows="5" wire:model="form.description" placeholder="Veuillez décrire en détail le problème rencontré ou l'intervention souhaitée..."></textarea>
            @error('form.description') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center gap-3 pt-6 border-t">
            <button class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm transition-colors" type="submit">
                {{ $ticketId ? 'Mettre à jour le ticket' : 'Créer le ticket' }}
            </button>
            <a class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 rounded-md transition-colors" href="{{ route('tickets.index') }}">Annuler</a>
        </div>
    </form>
</div>
