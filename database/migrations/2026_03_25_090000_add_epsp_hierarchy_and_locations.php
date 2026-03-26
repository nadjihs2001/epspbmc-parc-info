<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('structures')
                ->nullOnDelete();
            $table->string('responsable_nom')->nullable()->after('adresse');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->string('code')->nullable()->after('nom');
            $table->string('type')->default('service')->after('code');
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained('structures')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('nom');
            $table->string('type')->default('bureau')->index();
            $table->string('code')->nullable()->unique();
            $table->string('etage')->nullable();
            $table->text('details')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->foreignId('location_id')
                ->nullable()
                ->after('structure_id')
                ->constrained('locations')
                ->nullOnDelete();
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('location_id')
                ->nullable()
                ->after('structure_id')
                ->constrained('locations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
        });

        Schema::dropIfExists('locations');

        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['code', 'type']);
        });

        Schema::table('structures', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn('responsable_nom');
        });
    }
};
