<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->index('numero_serie');
            $table->index('mac');
            $table->index('statut');
            $table->index('marque');
            $table->index('modele');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->index('priorite');
            $table->index('statut');
            $table->index('ouvert_le');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['actif']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['priorite']);
            $table->dropIndex(['statut']);
            $table->dropIndex(['ouvert_le']);
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->dropIndex(['numero_serie']);
            $table->dropIndex(['mac']);
            $table->dropIndex(['statut']);
            $table->dropIndex(['marque']);
            $table->dropIndex(['modele']);
        });
    }
};
