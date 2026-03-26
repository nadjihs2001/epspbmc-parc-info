<?php

use App\Http\Controllers\InventoryExportController;
use App\Livewire\Equipment\EquipmentForm;
use App\Livewire\Equipment\EquipmentIndex;
use App\Livewire\Inventory\InventoryIndex;
use App\Livewire\Intervenant\IntervenantForm;
use App\Livewire\Intervenant\IntervenantIndex;
use App\Livewire\Assignment\AssignmentForm;
use App\Livewire\Assignment\AssignmentIndex;
use App\Livewire\License\LicenseForm;
use App\Livewire\License\LicenseIndex;
use App\Livewire\Maintenance\MaintenancePlanForm;
use App\Livewire\Maintenance\MaintenancePlanIndex;
use App\Livewire\Location\LocationForm;
use App\Livewire\Location\LocationIndex;
use App\Livewire\Network\IpRangeForm;
use App\Livewire\Network\IpRangeIndex;
use App\Livewire\Structure\StructureForm;
use App\Livewire\Structure\StructureIndex;
use App\Livewire\Ticket\TicketForm;
use App\Livewire\Ticket\TicketIndex;
use App\Livewire\User\UserForm;
use App\Livewire\User\UserIndex;
use App\Models\Alert;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\MaintenancePlan;
use App\Models\SoftwareLicense;
use App\Models\Structure;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {
    Route::get('/', function () {
        return auth()->check()
            ? redirect()->route('dashboard')
            : redirect()->route('login');
    })->name('home');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            abort_unless(auth()->user()?->can('dashboard.view'), 403);

            $structureId = auth()->user()?->structure_id;

            $stats = [
                'equipments' => Equipment::query()
                    ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                    ->count(),
                'tickets_open' => Ticket::query()
                    ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                    ->whereIn('statut', ['ouvert', 'en_cours'])
                    ->count(),
                'alerts' => Alert::query()
                    ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                    ->whereNull('resolved_at')
                    ->count(),
                'locations' => Location::query()
                    ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                    ->count(),
                'structures' => Structure::query()
                    ->when($structureId, function ($query) use ($structureId) {
                        $query->where(function ($nested) use ($structureId) {
                            $nested->where('id', $structureId)
                                ->orWhere('parent_id', $structureId);
                        });
                    })
                    ->count(),
            ];

            $urgentTickets = Ticket::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->whereIn('statut', ['ouvert', 'en_cours', 'en_attente'])
                ->whereIn('priorite', ['haute', 'critique'])
                ->latest()
                ->limit(5)
                ->get();

            $licensesExpiringSoon = SoftwareLicense::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->whereNotNull('expiration')
                ->whereDate('expiration', '<=', now()->addDays(30))
                ->orderBy('expiration')
                ->limit(5)
                ->get();

            $unresolvedAlerts = Alert::query()
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->whereNull('resolved_at')
                ->latest()
                ->limit(5)
                ->get();

            $upcomingMaintenance = MaintenancePlan::query()
                ->with('equipment')
                ->where('statut', 'actif')
                ->whereNotNull('prochain')
                ->when($structureId, fn ($q) => $q->whereHas('equipment', fn ($equipmentQuery) => $equipmentQuery->where('structure_id', $structureId)))
                ->orderBy('prochain')
                ->limit(5)
                ->get();

            $equipmentByStructure = Equipment::query()
                ->select('structure_id', DB::raw('COUNT(*) as total'))
                ->when($structureId, fn ($q) => $q->where('structure_id', $structureId))
                ->with('structure:id,nom')
                ->groupBy('structure_id')
                ->orderByDesc('total')
                ->limit(6)
                ->get();

            return view('dashboard', compact('stats', 'urgentTickets', 'licensesExpiringSoon', 'unresolvedAlerts', 'upcomingMaintenance', 'equipmentByStructure'));
        })->name('dashboard');

        Route::get('/structures', StructureIndex::class)->middleware('permission:structures.view')->name('structures.index');
        Route::get('/structures/create', StructureForm::class)->middleware('permission:structures.create')->name('structures.create');
        Route::get('/structures/{structureId}/edit', StructureForm::class)->middleware('permission:structures.update')->name('structures.edit');

        Route::get('/users', UserIndex::class)->middleware('permission:users.view')->name('users.index');
        Route::get('/users/create', UserForm::class)->middleware('permission:users.create')->name('users.create');
        Route::get('/users/{userId}/edit', UserForm::class)->middleware('permission:users.update')->name('users.edit');

        Route::get('/inventory', InventoryIndex::class)->middleware('permission:inventory.view')->name('inventory.index');
        Route::get('/inventory/export/csv', [InventoryExportController::class, 'csv'])->middleware('permission:inventory.export')->name('inventory.export.csv');
        Route::get('/inventory/print', [InventoryExportController::class, 'print'])->middleware('permission:inventory.view')->name('inventory.print');

        Route::get('/maintenance', MaintenancePlanIndex::class)->middleware('permission:maintenance.view')->name('maintenance.index');
        Route::get('/maintenance/create', MaintenancePlanForm::class)->middleware('permission:maintenance.manage')->name('maintenance.create');
        Route::get('/maintenance/{planId}/edit', MaintenancePlanForm::class)->middleware('permission:maintenance.manage')->name('maintenance.edit');

        Route::get('/locations', LocationIndex::class)->middleware('permission:locations.view')->name('locations.index');
        Route::get('/locations/create', LocationForm::class)->middleware('permission:locations.create')->name('locations.create');
        Route::get('/locations/{locationId}/edit', LocationForm::class)->middleware('permission:locations.update')->name('locations.edit');

        Route::get('/equipments', EquipmentIndex::class)->middleware('permission:equipments.view')->name('equipments.index');
        Route::get('/equipments/create', EquipmentForm::class)->middleware('permission:equipments.create')->name('equipments.create');
        Route::get('/equipments/{equipmentId}/edit', EquipmentForm::class)->middleware('permission:equipments.update')->name('equipments.edit');

        Route::get('/assignments', AssignmentIndex::class)->middleware('permission:assignments.view')->name('assignments.index');
        Route::get('/assignments/create', AssignmentForm::class)->middleware('permission:assignments.create')->name('assignments.create');
        Route::get('/assignments/{assignmentId}/edit', AssignmentForm::class)->middleware('permission:assignments.update')->name('assignments.edit');

        Route::get('/tickets', TicketIndex::class)->middleware('permission:tickets.view')->name('tickets.index');
        Route::get('/tickets/create', TicketForm::class)->middleware('permission:tickets.create')->name('tickets.create');
        Route::get('/tickets/{ticketId}/edit', TicketForm::class)->middleware('permission:tickets.update')->name('tickets.edit');

        Route::get('/intervenants', IntervenantIndex::class)->middleware('permission:intervenants.view')->name('intervenants.index');
        Route::get('/intervenants/create', IntervenantForm::class)->middleware('permission:intervenants.create')->name('intervenants.create');
        Route::get('/intervenants/{intervenantId}/edit', IntervenantForm::class)->middleware('permission:intervenants.update')->name('intervenants.edit');

        Route::get('/licenses', LicenseIndex::class)->middleware('permission:licenses.view')->name('licenses.index');
        Route::get('/licenses/create', LicenseForm::class)->middleware('permission:licenses.create')->name('licenses.create');
        Route::get('/licenses/{licenseId}/edit', LicenseForm::class)->middleware('permission:licenses.update')->name('licenses.edit');

        Route::get('/network/ranges', IpRangeIndex::class)->middleware('permission:network.view')->name('network.ranges.index');
        Route::get('/network/ranges/create', IpRangeForm::class)->middleware('permission:network.update')->name('network.ranges.create');
        Route::get('/network/ranges/{rangeId}/edit', IpRangeForm::class)->middleware('permission:network.update')->name('network.ranges.edit');
    });

    require __DIR__.'/auth.php';
});
