<?php

namespace App\Livewire\Equipment;

use App\Livewire\Concerns\AuthorizesModuleAccess;
use App\Models\Equipment;
use App\Models\EquipmentLocationHistory;
use App\Models\EquipmentType;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EquipmentForm extends Component
{
    use AuthorizesModuleAccess;

    public ?int $equipmentId = null;

    public array $form = [
        'code_inventaire' => '',
        'type_id' => null,
        'category_id' => null,
        'marque' => '',
        'modele' => '',
        'statut' => 'actif',
        'structure_id' => null,
        'location_id' => null,
        'salle' => '',
        'current_location' => '',
    ];

    public array $translations = [
        'fr' => ['nom' => '', 'description' => '', 'localisation' => ''],
        'ar' => ['nom' => '', 'description' => '', 'localisation' => ''],
        'en' => ['nom' => '', 'description' => '', 'localisation' => ''],
    ];

    public string $locationChangeNote = '';

    public function mount(?int $equipmentId = null): void
    {
        $this->equipmentId = $equipmentId;
        $this->form['structure_id'] = $this->scopedStructureId() ?? Auth::user()?->structure_id;

        if ($equipmentId) {
            $equipment = Equipment::findOrFail($equipmentId);
            $this->authorizeRecordAccess($equipment, 'equipments.update');
            $this->form = array_merge($this->form, $equipment->only(array_keys($this->form)));
            foreach ($this->translations as $locale => $fields) {
                $this->translations[$locale] = [
                    'nom' => $equipment->translate($locale)?->nom ?? '',
                    'description' => $equipment->translate($locale)?->description ?? '',
                    'localisation' => $equipment->translate($locale)?->localisation ?? '',
                ];
            }

            return;
        }

        $this->authorizePermission('equipments.create');
    }

    public function save(): void
    {
        $existingEquipment = $this->equipmentId ? Equipment::findOrFail($this->equipmentId) : null;

        if ($existingEquipment) {
            $this->authorizeRecordAccess($existingEquipment, 'equipments.update');
        } else {
            $this->authorizePermission('equipments.create');
        }

        $this->form = $this->lockStructureToAuthenticatedUser($this->form);

        $this->validate([
            'form.code_inventaire' => 'required|unique:equipments,code_inventaire,' . $this->equipmentId,
            'form.type_id' => 'required|exists:equipment_types,id',
            'form.structure_id' => 'required|exists:structures,id',
            'form.location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(function ($query) {
                    if (($structureId = $this->form['structure_id'] ?? null) !== null) {
                        $query->where('structure_id', $structureId);
                    }
                }),
            ],
            'form.current_location' => 'nullable|string|max:255',
            'translations.fr.nom' => 'required|string',
            'locationChangeNote' => 'nullable|string|max:1000',
        ]);

        $previousLocation = $this->normalizeLocation($existingEquipment?->current_location);
        $selectedLocation = !empty($this->form['location_id'])
            ? Location::query()->find($this->form['location_id'])
            : null;
        $newLocation = $selectedLocation
            ? $selectedLocation->full_label
            : $this->normalizeLocation($this->form['current_location'] ?? null);
        $this->form['current_location'] = $newLocation;

        $equipment = Equipment::updateOrCreate(
            ['id' => $this->equipmentId],
            $this->form
        );

        foreach ($this->translations as $locale => $fields) {
            $equipment->translateOrNew($locale)->fill($fields);
        }

        $equipment->save();

        if (
            ($existingEquipment === null && $newLocation !== null) ||
            ($existingEquipment !== null && $previousLocation !== $newLocation)
        ) {
            EquipmentLocationHistory::create([
                'equipment_id' => $equipment->id,
                'user_id' => Auth::id(),
                'previous_location' => $previousLocation,
                'new_location' => $newLocation,
                'note' => $this->normalizeLocation($this->locationChangeNote),
                'changed_at' => now(),
            ]);
        }

        $this->dispatch('notify', message: 'Équipement enregistré');
        $this->redirect(route('equipments.index', [], false));
    }

    public function render()
    {
        $locationHistories = collect();

        if ($this->equipmentId) {
            $locationHistories = EquipmentLocationHistory::query()
                ->with('user')
                ->where('equipment_id', $this->equipmentId)
                ->orderByDesc('changed_at')
                ->limit(20)
                ->get();
        }

        return view('livewire.equipment.form', [
            'types' => EquipmentType::all(),
            'locations' => Location::query()
                ->when($this->scopedStructureId(), fn ($query, $structureId) => $query->where('structure_id', $structureId))
                ->orderBy('type')
                ->orderBy('nom')
                ->get(),
            'locationHistories' => $locationHistories,
        ])->layout('layouts.app');
    }

    protected function normalizeLocation(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
