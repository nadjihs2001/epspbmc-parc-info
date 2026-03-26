<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('structure_id')->constrained('structures');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->text('justification')->nullable();
            $table->string('signature_path')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
