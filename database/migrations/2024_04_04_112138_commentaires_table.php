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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id')->default(1); 
            $table->unsignedBigInteger('topic_id'); 
            $table->unsignedBigInteger('id_parent')->nullable();
            $table->text('content'); 
            $table->timestamp('created_at_comm')->useCurrent(); 
            $table->timestamps(); 

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade'); 
            $table->foreign('id_parent')->references('id')->on('commentaires')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
