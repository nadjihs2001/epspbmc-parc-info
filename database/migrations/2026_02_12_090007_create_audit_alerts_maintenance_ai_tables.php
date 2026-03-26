<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('message');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->enum('priorite', ['basse', 'moyenne', 'haute', 'critique'])->default('moyenne');
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('structure_id')->constrained('structures');
            $table->timestamps();
        });

        Schema::create('maintenance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->integer('periodicite_jours')->default(90);
            $table->date('prochain')->nullable();
            $table->enum('statut', ['actif', 'suspendu'])->default('actif');
            $table->timestamps();
        });

        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('type');
            $table->json('payload');
            $table->json('response')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->decimal('score_risque', 5, 2)->nullable();
            $table->text('recommendation')->nullable();
            $table->date('predicted_failure_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_insights');
        Schema::dropIfExists('ai_requests');
        Schema::dropIfExists('maintenance_plans');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('audit_logs');
    }
};
