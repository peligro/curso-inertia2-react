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
        Schema::create('users_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('telefono', 50);

            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('estados_id');
            $table->foreign('estados_id')->references('id')->on('estados')->onDelete('cascade');

            $table->unsignedBigInteger('perfiles_id');
            $table->foreign('perfiles_id')->references('id')->on('perfiles')->onDelete('cascade');
            
            $table->index(['telefono']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_metadata');
    }
};
