<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vlans', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('tag')->nullable();
            $table->foreignId('structure_id')->constrained('structures');
            $table->timestamps();
        });

        Schema::create('ip_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vlan_id')->nullable()->constrained('vlans');
            $table->string('cidr');
            $table->string('passerelle')->nullable();
            $table->string('dns1')->nullable();
            $table->string('dns2')->nullable();
            $table->foreignId('structure_id')->constrained('structures');
            $table->timestamps();
        });

        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ip_ranges_id')->nullable()->constrained('ip_ranges');
            $table->string('ip')->index();
            $table->enum('statut', ['libre', 'occupee', 'reservee'])->default('libre');
            $table->foreignId('equipment_id')->nullable()->constrained('equipments');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('structure_id')->constrained('structures');
            $table->timestamps();
        });

        Schema::create('switches', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('modele')->nullable();
            $table->string('ip_gestion')->nullable();
            $table->foreignId('structure_id')->constrained('structures');
            $table->timestamps();
        });

        Schema::create('switch_ports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('switch_id')->constrained('switches')->cascadeOnDelete();
            $table->string('port');
            $table->foreignId('equipment_id')->nullable()->constrained('equipments');
            $table->foreignId('vlan_id')->nullable()->constrained('vlans');
            $table->timestamps();
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->foreign('ip_id')->references('id')->on('ip_addresses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropForeign(['ip_id']);
        });
        Schema::dropIfExists('switch_ports');
        Schema::dropIfExists('switches');
        Schema::dropIfExists('ip_addresses');
        Schema::dropIfExists('ip_ranges');
        Schema::dropIfExists('vlans');
    }
};
