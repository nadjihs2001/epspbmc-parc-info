<div class="space-y-6">
    <h1 class="text-xl font-semibold">{{ $rangeId ? 'Modifier plage IP' : 'Nouvelle plage IP' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">CIDR</label>
                <input class="w-full px-3 py-2 border rounded" type="text" placeholder="192.168.1.0/24" wire:model.defer="form.cidr">
                @error('form.cidr') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Passerelle</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.passerelle">
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium">DNS 1</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.dns1">
            </div>
            <div>
                <label class="text-sm font-medium">DNS 2</label>
                <input class="w-full px-3 py-2 border rounded" type="text" wire:model.defer="form.dns2">
            </div>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-semibold text-white bg-slate-900 rounded" type="submit">
                Enregistrer
            </button>
            <a class="px-4 py-2 text-sm font-semibold border rounded" href="{{ route('network.ranges.index') }}">Annuler</a>
        </div>
    </form>
</div>
