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
            $table->enum('sexe', ['homme', 'femme'])->nullable(); // Ajout du champ "sexe"
            $table->timestamp('date_inscription')->useCurrent(); // Ajout du champ "date_inscription"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sexe');
            $table->dropColumn('date_inscription');
        });
    }
};
