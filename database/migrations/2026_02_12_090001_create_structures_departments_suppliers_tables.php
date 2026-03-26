<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type')->index();
            $table->string('code')->nullable()->unique();
            $table->string('adresse')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('structure_id')->constrained('structures')->cascadeOnDelete();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('contact')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('structures');
    }
};
