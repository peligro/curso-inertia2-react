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
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('slug', 100)->unique();
            $table->text('descripcion');
            $table->unsignedBigInteger('categorias_id');
            $table->foreign('categorias_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->string('foto');
            $table->datetime('fecha')->useCurrent();
            
            $table->index(['nombre', 'slug', 'foto','fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
