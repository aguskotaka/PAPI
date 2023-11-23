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
        Schema::create('reportposts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('author');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();


            $table->Foreign('author')->references('id')->on('users');
            $table->Foreign('post_id')->references('id')->on('posts');
            $table->Foreign('user_id')->references('id')->on('users');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportposts');
    }
};
