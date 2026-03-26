<?php

namespace Database\Seeders\Core;

use App\Models\Department;
use App\Models\Location;
use App\Models\Structure;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['structure_code' => 'DG', 'nom' => 'Bureau du directeur', 'type' => 'bureau', 'code' => 'LOC-DG-DIR', 'department_code' => 'DG-DIR'],
            ['structure_code' => 'DG', 'nom' => 'Bureau du secretariat', 'type' => 'bureau', 'code' => 'LOC-DG-SEC', 'department_code' => 'DG-SEC'],
            ['structure_code' => 'SDIH', 'nom' => 'Atelier informatique', 'type' => 'bureau_technique', 'code' => 'LOC-SDIH-ATELIER', 'department_code' => 'SDIH-TEC'],
            ['structure_code' => 'SIEGE', 'nom' => 'Salle de numerisation centrale', 'type' => 'numerisation', 'code' => 'LOC-SIEGE-NUM', 'department_code' => 'SIEGE-NUM'],
            ['structure_code' => 'POLY-01', 'nom' => 'Salle de numerisation', 'type' => 'numerisation', 'code' => 'LOC-POLY-01-NUM', 'department_code' => 'POLY-01-NUM'],
            ['structure_code' => 'POLY-01', 'nom' => 'Salle de soins 01', 'type' => 'local_medical', 'code' => 'LOC-POLY-01-MED-01', 'department_code' => 'POLY-01-MED'],
            ['structure_code' => 'POLY-02', 'nom' => 'Salle de numerisation', 'type' => 'numerisation', 'code' => 'LOC-POLY-02-NUM', 'department_code' => 'POLY-02-NUM'],
            ['structure_code' => 'POLY-02', 'nom' => 'Salle de soins 01', 'type' => 'local_medical', 'code' => 'LOC-POLY-02-MED-01', 'department_code' => 'POLY-02-MED'],
            ['structure_code' => 'CS-01', 'nom' => 'Salle de numerisation', 'type' => 'numerisation', 'code' => 'LOC-CS-01-NUM', 'department_code' => 'CS-01-NUM'],
            ['structure_code' => 'CS-01', 'nom' => 'Consultation 01', 'type' => 'local_medical', 'code' => 'LOC-CS-01-MED-01', 'department_code' => 'CS-01-MED'],
            ['structure_code' => 'CS-02', 'nom' => 'Salle de numerisation', 'type' => 'numerisation', 'code' => 'LOC-CS-02-NUM', 'department_code' => 'CS-02-NUM'],
            ['structure_code' => 'CS-02', 'nom' => 'Consultation 01', 'type' => 'local_medical', 'code' => 'LOC-CS-02-MED-01', 'department_code' => 'CS-02-MED'],
            ['structure_code' => 'PHARM', 'nom' => 'Reserve principale', 'type' => 'stockage', 'code' => 'LOC-PHARM-RES'],
            ['structure_code' => 'MAG', 'nom' => 'Depot informatique', 'type' => 'stockage', 'code' => 'LOC-MAG-INF'],
        ];

        foreach ($locations as $location) {
            $structureId = Structure::query()->where('code', $location['structure_code'])->value('id');
            $departmentId = !empty($location['department_code'])
                ? Department::query()->where('code', $location['department_code'])->value('id')
                : null;

            Location::updateOrCreate(
                ['code' => $location['code']],
                [
                    'structure_id' => $structureId,
                    'department_id' => $departmentId,
                    'parent_id' => null,
                    'nom' => $location['nom'],
                    'type' => $location['type'],
                    'etage' => null,
                    'details' => null,
                    'actif' => true,
                ]
            );
        }
    }
}
