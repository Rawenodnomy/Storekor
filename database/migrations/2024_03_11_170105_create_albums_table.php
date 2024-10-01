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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();



            $table->unsignedBigInteger('position_id');
            $table->foreign('position_id')
                ->references('id')->on('positions')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->string('name');




            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')
                ->references('id')->on('versions')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('photo');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
