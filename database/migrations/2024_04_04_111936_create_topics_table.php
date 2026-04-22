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
        Schema::create('topics', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id')->default(1); 
            $table->string('title'); 
            $table->text('content')->nullable(); 
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('image')->nullable();
            $table->timestamp('created_at_topic')->useCurrent(); 
            $table->timestamps(); 
        });
        
        Schema::create('theme_topic', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('theme_id');
        $table->unsignedBigInteger('topic_id');
        $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
        $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
