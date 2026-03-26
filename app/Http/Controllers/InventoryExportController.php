<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use App\Models\Structure;
use App\Services\Inventory\InventoryReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryExportController extends Controller
{
    public function csv(Request $request, InventoryReportService $inventoryReportService): StreamedResponse
    {
        abort_unless($request->user()?->can('inventory.export'), 403);

        $filters = $this->filters($request);
        $equipments = $inventoryReportService->query($filters, $request->user())->get();

        return response()->streamDownload(function () use ($equipments): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Code', 'Nom', 'Type', 'Structure', 'Local', 'Statut', 'Marque', 'Modele', 'Numero de serie', 'Date acquisition'], ';');

            foreach ($equipments as $equipment) {
                fputcsv($handle, [
                    $equipment->code_inventaire,
                    $equipment->nom,
                    $equipment->type?->nom,
                    $equipment->structure?->nom,
                    $equipment->location?->full_label ?? $equipment->current_location,
                    $equipment->statut,
                    $equipment->marque,
                    $equipment->modele,
                    $equipment->numero_serie,
                    optional($equipment->date_acquisition)->format('Y-m-d'),
                ], ';');
            }

            fclose($handle);
        }, 'inventaire-epsp-bmc.csv');
    }

    public function print(Request $request, InventoryReportService $inventoryReportService)
    {
        abort_unless($request->user()?->can('inventory.view'), 403);

        $filters = $this->filters($request);

        return view('inventory.print', [
            'equipments' => $inventoryReportService->query($filters, $request->user())->get(),
            'summary' => $inventoryReportService->summary($filters, $request->user()),
            'selectedStructure' => !empty($filters['structure_id']) ? Structure::query()->find($filters['structure_id']) : null,
            'selectedType' => !empty($filters['type_id']) ? EquipmentType::query()->find($filters['type_id']) : null,
            'filters' => $filters,
        ]);
    }

    protected function filters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'structure_id' => ['nullable', 'integer', 'exists:structures,id'],
            'type_id' => ['nullable', 'integer', 'exists:equipment_types,id'],
            'statut' => ['nullable', 'string', 'max:50'],
        ]);
    }
}
