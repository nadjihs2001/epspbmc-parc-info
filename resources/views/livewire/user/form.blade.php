<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $userId ? 'Modifier utilisateur' : 'Nouvel utilisateur' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Nom complet</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.name">
                @error('form.name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Email</label>
                <input class="w-full px-3 py-2 border rounded" type="email" wire:model.defer="form.email">
                @error('form.email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

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
                <label class="text-sm font-medium">Service</label>
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
                <label class="text-sm font-medium">Role</label>
                <select class="w-full px-3 py-2 border rounded" wire:model.defer="form.role">
                    <option value="">Selectionner</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('form.role') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" wire:model.defer="form.actif">
                    <span>Compte actif</span>
                </label>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Fonction</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.fonction">
                @error('form.fonction') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Telephone</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.telephone">
                @error('form.telephone') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">Mot de passe {{ $userId ? '(laisser vide pour conserver)' : '' }}</label>
                <input class="w-full px-3 py-2 border rounded" type="password" wire:model.defer="form.password">
                @error('form.password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Confirmation du mot de passe</label>
                <input class="w-full px-3 py-2 border rounded" type="password" wire:model.defer="form.password_confirmation">
                @error('form.password_confirmation') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('users.index') }}">Annuler</a>
        </div>
    </form>
</div>
