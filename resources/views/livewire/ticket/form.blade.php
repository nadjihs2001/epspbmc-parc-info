<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $ticketId ? 'Modifier ticket' : 'Nouveau ticket' }}</h1>
            <p class="text-sm text-slate-500">Ouverture d'une demande d'intervention ou signalement de panne.</p>
        </div>
        <div class="text-sm px-3 py-1 bg-slate-100 border rounded-full text-slate-600 font-medium">
            Structure: <span class="text-slate-900">{{ auth()->user()?->structure?->nom ?? 'Non définie' }}</span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3 items-start">
        <form wire:submit.prevent="save" class="lg:col-span-2 space-y-6 bg-white p-6 rounded-xl shadow-sm border">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Code ticket *</label>
                    <input class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" type="text" wire:model="form.code">
                    @error('form.code') <div class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Type d'intervention *</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.type">
                        <option value="panne">🔧 Panne matérielle ou logicielle</option>
                        <option value="maintenance_preventive">📅 Maintenance préventive</option>
                    </select>
                    @error('form.type') <div class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Équipement concerné</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.equipment_id">
                        <option value="">-- Sélectionner l'équipement --</option>
                        @foreach ($equipments as $equipment)
                            <option value="{{ $equipment->id }}">{{ $equipment->code_inventaire }} - {{ $equipment->nom }} ({{ $equipment->marque }})</option>
                        @endforeach
                    </select>
                    @error('form.equipment_id') <div class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Priorité demandée *</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.priorite">
                        <option value="basse">🔵 Basse</option>
                        <option value="moyenne">🟢 Moyenne</option>
                        <option value="haute">🟠 Haute</option>
                        <option value="critique">🔴 Critique</option>
                    </select>
                    @error('form.priorite') <div class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-semibold text-slate-700">Description du problème *</label>
                    <button
                        type="button"
                        wire:click="runAiDiagnosis"
                        wire:loading.attr="disabled"
                        class="text-xs inline-flex items-center px-2 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md hover:bg-indigo-100 transition-colors"
                    >
                        <svg wire:loading.remove wire:target="runAiDiagnosis" class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <svg wire:loading wire:target="runAiDiagnosis" class="animate-spin w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        {{ $isDiagnosing ? 'Analyse...' : 'Aide IA' }}
                    </button>
                </div>
                <textarea
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-400"
                    rows="6"
                    wire:model="form.description"
                    placeholder="Veuillez décrire en détail le problème rencontré..."
                ></textarea>
                @error('form.description') <div class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Intervenant affecté</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.intervenant_id">
                        <option value="">-- Non affecté --</option>
                        @foreach ($intervenants as $intervenant)
                            <option value="{{ $intervenant->id }}">{{ $intervenant->nom }} ({{ $intervenant->specialite }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Statut du ticket *</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" wire:model="form.statut">
                        <option value="ouvert">Ouvert</option>
                        <option value="en_cours">En cours</option>
                        <option value="en_attente">En attente</option>
                        <option value="resolu">Résolu</option>
                        <option value="clos">Clos</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t">
                <button class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-md transition-all active:scale-95" type="submit">
                    {{ $ticketId ? 'Mettre à jour le ticket' : 'Créer le ticket' }}
                </button>
                <a class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition-colors" href="{{ route('tickets.index') }}">Annuler</a>
            </div>
        </form>

        <div class="space-y-6">
            @if ($aiResults)
                <div class="bg-indigo-600 text-white rounded-xl shadow-lg border border-indigo-700 overflow-hidden animate-in slide-in-from-right-10 duration-500">
                    <div class="px-4 py-3 bg-indigo-700 flex items-center justify-between">
                        <h3 class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Analyse de l'Assistant IA
                        </h3>
                        <button type="button" wire:click="$set('aiResults', null)" class="text-indigo-200 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-5 space-y-4 text-sm">
                        <div class="bg-indigo-800/50 p-3 rounded-lg border border-indigo-400/30 italic">
                            {{ $aiResults['ai_analysis'] }}
                        </div>

                        <div>
                            <div class="font-bold mb-2 flex items-center gap-2 text-indigo-100 uppercase tracking-wider text-[10px]">
                                <span>🔍 Causes probables</span>
                                <div class="h-px flex-1 bg-indigo-400/30"></div>
                            </div>
                            <ul class="space-y-1">
                                @foreach($aiResults['probable_causes'] as $cause)
                                    <li class="flex items-start gap-2">
                                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-indigo-300"></span>
                                        {{ $cause }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <div class="font-bold mb-2 flex items-center gap-2 text-indigo-100 uppercase tracking-wider text-[10px]">
                                <span>🛠️ Actions recommandées</span>
                                <div class="h-px flex-1 bg-indigo-400/30"></div>
                            </div>
                            <ul class="space-y-1">
                                @foreach($aiResults['recommended_actions'] as $action)
                                    <li class="flex items-start gap-2 text-white/90">
                                        <svg class="w-4 h-4 mt-0.5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $action }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <button
                            type="button"
                            wire:click="applyAiSuggestion"
                            class="w-full py-2 bg-white text-indigo-700 font-bold rounded-lg shadow-sm hover:bg-indigo-50 transition-colors"
                        >
                            Insérer dans la description
                        </button>
                    </div>
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4">
                <h3 class="font-bold text-slate-800">Informations complémentaires</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-slate-500 font-medium">Créé par</span>
                        <span class="text-slate-900 font-bold">{{ auth()->user()?->name }}</span>
                    </div>
                    @if($ticketId)
                        <div class="flex justify-between items-center pb-2 border-b">
                            <span class="text-slate-500 font-medium">Ouvert le</span>
                            <span class="text-slate-900 font-bold">{{ $form->ticket?->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
