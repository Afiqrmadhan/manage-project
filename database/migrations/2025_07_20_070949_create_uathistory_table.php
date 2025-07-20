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
        Schema::create('uathistory', function (Blueprint $table) {
            $table->id();
            $table->string('ProjectManager');
            $table->unsignedBigInteger('ProjectManagerId');
            $table->string('Title');
            $table->unsignedBigInteger('ProjectId');
            $table->enum('Status', ['On Progress', 'Finish']);
            $table->timestamps();

            $table->foreign('ProjectManagerId')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uathistory');
    }
};
