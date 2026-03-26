<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventaire EPSP Bachir Mentouri</title>
        <style>
            body { font-family: Arial, sans-serif; color: #0f172a; margin: 24px; }
            h1, h2 { margin: 0 0 8px; }
            .meta { margin-bottom: 20px; color: #475569; font-size: 14px; }
            .summary { display: flex; gap: 12px; margin: 16px 0 24px; flex-wrap: wrap; }
            .card { border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px 16px; min-width: 140px; }
            table { width: 100%; border-collapse: collapse; font-size: 12px; }
            th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
            th { background: #f1f5f9; }
            @media print { body { margin: 0; } }
        </style>
    </head>
    <body>
        <h1>Inventaire EPSP Bachir Mentouri</h1>
        <div class="meta">
            <div>Date d'edition: {{ now()->format('Y-m-d H:i') }}</div>
            <div>Structure: {{ $selectedStructure?->nom ?? 'Toutes' }}</div>
            <div>Type: {{ $selectedType?->nom ?? 'Tous' }}</div>
            <div>Statut: {{ $filters['statut'] ?? 'Tous' }}</div>
        </div>

        <div class="summary">
            <div class="card"><strong>Total</strong><br>{{ $summary['total'] }}</div>
            <div class="card"><strong>Actifs</strong><br>{{ $summary['actif'] }}</div>
            <div class="card"><strong>En panne</strong><br>{{ $summary['panne'] }}</div>
            <div class="card"><strong>Maintenance</strong><br>{{ $summary['maintenance'] }}</div>
            <div class="card"><strong>Reformes</strong><br>{{ $summary['reforme'] }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Structure</th>
                    <th>Local</th>
                    <th>Statut</th>
                    <th>Serie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipments as $equipment)
                    <tr>
                        <td>{{ $equipment->code_inventaire }}</td>
                        <td>{{ $equipment->nom }}</td>
                        <td>{{ $equipment->type?->nom ?? '-' }}</td>
                        <td>{{ $equipment->structure?->nom ?? '-' }}</td>
                        <td>{{ $equipment->location?->full_label ?? $equipment->current_location ?? '-' }}</td>
                        <td>{{ ucfirst($equipment->statut) }}</td>
                        <td>{{ $equipment->numero_serie ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Aucune ligne d'inventaire trouvee.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
