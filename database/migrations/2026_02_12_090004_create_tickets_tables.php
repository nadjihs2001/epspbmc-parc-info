<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('equipment_id')->nullable()->constrained('equipments');
            $table->foreignId('structure_id')->constrained('structures');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->enum('priorite', ['basse', 'moyenne', 'haute', 'critique'])->default('moyenne');
            $table->enum('statut', ['ouvert', 'en_cours', 'en_attente', 'resolu', 'clos'])->default('ouvert');
            $table->integer('sla_minutes')->nullable();
            $table->text('description');
            $table->text('diagnostic')->nullable();
            $table->text('solution')->nullable();
            $table->decimal('cout_total', 12, 2)->default(0);
            $table->timestamp('ouvert_le')->nullable();
            $table->timestamp('cloture_le')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->string('nom_piece');
            $table->integer('quantite')->default(1);
            $table->decimal('cout', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ticket_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->string('path_pdf');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_reports');
        Schema::dropIfExists('ticket_parts');
        Schema::dropIfExists('tickets');
    }
};
