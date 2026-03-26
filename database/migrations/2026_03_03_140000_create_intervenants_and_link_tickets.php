<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('intervenants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('specialite')->nullable();
            $table->foreignId('structure_id')->constrained('structures');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        if (Schema::hasColumn('tickets', 'intervenant_id')) {
            try {
                Schema::table('tickets', function (Blueprint $table) {
                    $table->dropForeign(['intervenant_id']);
                });
            } catch (\Throwable $e) {
                // Ignore if the old constraint does not exist.
            }

            DB::table('tickets')->update(['intervenant_id' => null]);

            Schema::table('tickets', function (Blueprint $table) {
                $table->foreign('intervenant_id')
                    ->references('id')
                    ->on('intervenants')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tickets', 'intervenant_id')) {
            try {
                Schema::table('tickets', function (Blueprint $table) {
                    $table->dropForeign(['intervenant_id']);
                });
            } catch (\Throwable $e) {
                // Ignore if the constraint does not exist.
            }

            Schema::table('tickets', function (Blueprint $table) {
                $table->foreign('intervenant_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }

        Schema::dropIfExists('intervenants');
    }
};
