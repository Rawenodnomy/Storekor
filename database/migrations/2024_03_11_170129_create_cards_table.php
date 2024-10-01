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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id');
            $table->foreign('position_id')
                ->references('id')->on('positions')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('member');

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('photo');
            $table->integer('price');
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')
                ->references('id')->on('albums')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
