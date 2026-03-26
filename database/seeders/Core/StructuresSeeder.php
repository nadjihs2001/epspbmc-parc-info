<?php

namespace Database\Seeders\Core;

use App\Models\Department;
use App\Models\Structure;
use Illuminate\Database\Seeder;

class StructuresSeeder extends Seeder
{
    public function run(): void
    {
        $structures = [
            ['nom' => 'EPSP Bachir Mentouri', 'type' => 'epsp', 'code' => 'EPSP-BMC', 'responsable_nom' => 'Direction générale'],
            ['nom' => 'Siège - Administration générale', 'type' => 'siege', 'code' => 'SIEGE', 'parent_code' => 'EPSP-BMC'],
            ['nom' => 'Direction générale (DG)', 'type' => 'direction', 'code' => 'DG', 'parent_code' => 'SIEGE'],
            ['nom' => 'SDRH', 'type' => 'service', 'code' => 'SDRH', 'parent_code' => 'SIEGE'],
            ['nom' => 'SDFM', 'type' => 'service', 'code' => 'SDFM', 'parent_code' => 'SIEGE'],
            ['nom' => 'SDSS', 'type' => 'service', 'code' => 'SDSS', 'parent_code' => 'SIEGE'],
            ['nom' => 'SDMM', 'type' => 'service', 'code' => 'SDMM', 'parent_code' => 'SIEGE'],
            ['nom' => 'SDIH', 'type' => 'service', 'code' => 'SDIH', 'parent_code' => 'SIEGE'],
            ['nom' => 'Pharmacie', 'type' => 'pharmacie', 'code' => 'PHARM', 'parent_code' => 'EPSP-BMC'],
            ['nom' => 'Magasin', 'type' => 'magasin', 'code' => 'MAG', 'parent_code' => 'EPSP-BMC'],
            ['nom' => 'Polyclinique 01', 'type' => 'polyclinique', 'code' => 'POLY-01', 'parent_code' => 'EPSP-BMC'],
            ['nom' => 'Polyclinique 02', 'type' => 'polyclinique', 'code' => 'POLY-02', 'parent_code' => 'EPSP-BMC'],
            ['nom' => 'Centre de sante 01', 'type' => 'centre_sante', 'code' => 'CS-01', 'parent_code' => 'EPSP-BMC'],
            ['nom' => 'Centre de sante 02', 'type' => 'centre_sante', 'code' => 'CS-02', 'parent_code' => 'EPSP-BMC'],
        ];

        foreach ($structures as $item) {
            $parentCode = $item['parent_code'] ?? null;
            unset($item['parent_code']);

            Structure::updateOrCreate(
                ['code' => $item['code']],
                array_merge($item, [
                    'parent_id' => $parentCode ? Structure::query()->where('code', $parentCode)->value('id') : null,
                    'actif' => true,
                ])
            );
        }

        $departments = [
            ['structure_code' => 'DG', 'nom' => 'Direction', 'code' => 'DG-DIR', 'type' => 'bureau'],
            ['structure_code' => 'DG', 'nom' => 'Secretariat', 'code' => 'DG-SEC', 'type' => 'secretariat'],
            ['structure_code' => 'DG', 'nom' => 'Bureaux administratifs', 'code' => 'DG-ADM', 'type' => 'bureau'],
            ['structure_code' => 'SDIH', 'nom' => 'Bureaux administratifs', 'code' => 'SDIH-ADM', 'type' => 'bureau'],
            ['structure_code' => 'SDIH', 'nom' => 'Bureaux techniques', 'code' => 'SDIH-TEC', 'type' => 'bureau_technique'],
            ['structure_code' => 'SIEGE', 'nom' => 'Salle de numerisation', 'code' => 'SIEGE-NUM', 'type' => 'numerisation'],
            ['structure_code' => 'POLY-01', 'nom' => 'Secretariat', 'code' => 'POLY-01-SEC', 'type' => 'secretariat'],
            ['structure_code' => 'POLY-01', 'nom' => 'Salle de numerisation', 'code' => 'POLY-01-NUM', 'type' => 'numerisation'],
            ['structure_code' => 'POLY-01', 'nom' => 'Locaux medicaux', 'code' => 'POLY-01-MED', 'type' => 'local_medical'],
            ['structure_code' => 'POLY-02', 'nom' => 'Secretariat', 'code' => 'POLY-02-SEC', 'type' => 'secretariat'],
            ['structure_code' => 'POLY-02', 'nom' => 'Salle de numerisation', 'code' => 'POLY-02-NUM', 'type' => 'numerisation'],
            ['structure_code' => 'POLY-02', 'nom' => 'Locaux medicaux', 'code' => 'POLY-02-MED', 'type' => 'local_medical'],
            ['structure_code' => 'CS-01', 'nom' => 'Secretariat', 'code' => 'CS-01-SEC', 'type' => 'secretariat'],
            ['structure_code' => 'CS-01', 'nom' => 'Salle de numerisation', 'code' => 'CS-01-NUM', 'type' => 'numerisation'],
            ['structure_code' => 'CS-01', 'nom' => 'Locaux medicaux', 'code' => 'CS-01-MED', 'type' => 'local_medical'],
            ['structure_code' => 'CS-02', 'nom' => 'Secretariat', 'code' => 'CS-02-SEC', 'type' => 'secretariat'],
            ['structure_code' => 'CS-02', 'nom' => 'Salle de numerisation', 'code' => 'CS-02-NUM', 'type' => 'numerisation'],
            ['structure_code' => 'CS-02', 'nom' => 'Locaux medicaux', 'code' => 'CS-02-MED', 'type' => 'local_medical'],
        ];

        foreach ($departments as $department) {
            $structureId = Structure::query()->where('code', $department['structure_code'])->value('id');

            Department::updateOrCreate(
                ['code' => $department['code']],
                [
                    'structure_id' => $structureId,
                    'nom' => $department['nom'],
                    'type' => $department['type'],
                    'actif' => true,
                ]
            );
        }
    }
}
