<?php

namespace App\Livewire\Forms;

use App\Models\Equipment;
use App\Models\EquipmentLocationHistory;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EquipmentForm extends Form
{
    public ?Equipment $equipment = null;

    public $code_inventaire = '';
    public $type_id = null;
    public $category_id = null;
    public $marque = '';
    public $modele = '';
    public $numero_serie = '';
    public $mac = '';
    public $os = '';
    public $statut = 'actif';
    public $date_acquisition = '';
    public $garantie_fin = '';
    public $fournisseur_id = null;
    public $structure_id = null;
    public $location_id = null;
    public $salle = '';
    public $current_location = '';
    public $user_id = null;
    public $notes = '';

    public array $translations = [
        'fr' => ['nom' => '', 'description' => '', 'localisation' => ''],
        'ar' => ['nom' => '', 'description' => '', 'localisation' => ''],
        'en' => ['nom' => '', 'description' => '', 'localisation' => ''],
    ];

    public string $locationChangeNote = '';

    public function setEquipment(Equipment $equipment)
    {
        $this->equipment = $equipment;

        $this->code_inventaire = $equipment->code_inventaire;
        $this->type_id = $equipment->type_id;
        $this->category_id = $equipment->category_id;
        $this->marque = $equipment->marque;
        $this->modele = $equipment->modele;
        $this->numero_serie = $equipment->numero_serie;
        $this->mac = $equipment->mac;
        $this->os = $equipment->os;
        $this->statut = $equipment->statut;
        $this->date_acquisition = $equipment->date_acquisition?->format('Y-m-d');
        $this->garantie_fin = $equipment->garantie_fin?->format('Y-m-d');
        $this->fournisseur_id = $equipment->fournisseur_id;
        $this->structure_id = $equipment->structure_id;
        $this->location_id = $equipment->location_id;
        $this->salle = $equipment->salle;
        $this->current_location = $equipment->current_location;
        $this->user_id = $equipment->user_id;
        $this->notes = $equipment->notes;

        foreach ($this->translations as $locale => $fields) {
            $this->translations[$locale] = [
                'nom' => $equipment->translate($locale)?->nom ?? '',
                'description' => $equipment->translate($locale)?->description ?? '',
                'localisation' => $equipment->translate($locale)?->localisation ?? '',
            ];
        }
    }

    public function rules()
    {
        return [
            'code_inventaire' => 'required|unique:equipments,code_inventaire,' . ($this->equipment->id ?? 'NULL'),
            'type_id' => 'required|exists:equipment_types,id',
            'category_id' => 'nullable|exists:equipment_categories,id',
            'structure_id' => 'required|exists:structures,id',
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(function ($query) {
                    if ($this->structure_id !== null) {
                        $query->where('structure_id', $this->structure_id);
                    }
                }),
            ],
            'current_location' => 'nullable|string|max:255',
            'translations.fr.nom' => 'required|string',
            'locationChangeNote' => 'nullable|string|max:1000',
            'date_acquisition' => 'nullable|date',
            'garantie_fin' => 'nullable|date',
            'fournisseur_id' => 'nullable|exists:suppliers,id',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    public function store()
    {
        $this->validate();

        $previousLocation = $this->normalizeLocation($this->equipment?->current_location);
        $selectedLocation = !empty($this->location_id)
            ? Location::query()->find($this->location_id)
            : null;
        $newLocation = $selectedLocation
            ? $selectedLocation->full_label
            : $this->normalizeLocation($this->current_location ?? null);

        $this->current_location = $newLocation;

        $data = $this->except(['equipment', 'translations', 'locationChangeNote']);

        if ($this->equipment) {
            $this->equipment->update($data);
            $equipment = $this->equipment;
        } else {
            $equipment = Equipment::create($data);
        }

        foreach ($this->translations as $locale => $fields) {
            $equipment->translateOrNew($locale)->fill($fields);
        }

        $equipment->save();

        if (
            ($this->equipment === null && $newLocation !== null) ||
            ($this->equipment !== null && $previousLocation !== $newLocation)
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

        return $equipment;
    }

    protected function normalizeLocation(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}
