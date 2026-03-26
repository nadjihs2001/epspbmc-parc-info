<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('software_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type')->nullable();
            $table->string('cle')->nullable();
            $table->integer('nb_installations')->default(1);
            $table->date('expiration')->nullable();
            $table->integer('alert_days')->default(30);
            $table->foreignId('fournisseur_id')->nullable()->constrained('suppliers');
            $table->foreignId('structure_id')->constrained('structures');
            $table->timestamps();
        });

        Schema::create('license_installations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('software_license_id')->constrained('software_licenses')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->date('installe_le')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_installations');
        Schema::dropIfExists('software_licenses');
    }
};
