<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('equipment_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_type_id')->constrained('equipment_types')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->unique(['equipment_type_id', 'locale']);
        });

        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('equipment_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_category_id')->constrained('equipment_categories')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('nom');
            $table->unique(['equipment_category_id', 'locale'],191);
        });

        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('code_inventaire')->unique();
            $table->foreignId('type_id')->constrained('equipment_types');
            $table->foreignId('category_id')->nullable()->constrained('equipment_categories');
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('numero_serie')->nullable();
            $table->string('mac')->nullable();
            $table->unsignedBigInteger('ip_id')->nullable();
            $table->string('os')->nullable();
            $table->enum('statut', ['actif', 'panne', 'maintenance', 'reforme'])->default('actif');
            $table->date('date_acquisition')->nullable();
            $table->date('garantie_fin')->nullable();
            $table->foreignId('fournisseur_id')->nullable()->constrained('suppliers');
            $table->foreignId('structure_id')->constrained('structures');
            $table->string('salle')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('localisation')->nullable();
            $table->unique(['equipment_id', 'locale']);
        });

        Schema::create('equipment_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_photos');
        Schema::dropIfExists('equipment_translations');
        Schema::dropIfExists('equipments');
        Schema::dropIfExists('equipment_category_translations');
        Schema::dropIfExists('equipment_categories');
        Schema::dropIfExists('equipment_type_translations');
        Schema::dropIfExists('equipment_types');
    }
};
