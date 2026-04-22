<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('etudiant'); // Ajout du champ "role"
            $table->date('date_naissance')->nullable(); // Ajout du champ "date_naissance"
            $table->string('adresse')->nullable(); // Ajout du champ "adresse"
            $table->string('telephone')->nullable(); // Ajout du champ "telephone"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('date_naissance');
            $table->dropColumn('adresse');
            $table->dropColumn('telephone');
        });
    }
};
