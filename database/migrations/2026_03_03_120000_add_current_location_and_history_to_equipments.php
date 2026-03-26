<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->string('current_location')->nullable();
        });

        Schema::create('equipment_location_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('previous_location')->nullable();
            $table->string('new_location')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_location_histories');

        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('current_location');
        });
    }
};
