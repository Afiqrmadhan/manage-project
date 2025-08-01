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
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('ProjectManager');
            $table->unsignedBigInteger('ProjectManagerId');
            $table->string('Title');
            $table->string('ClientCompany');
            $table->string('ClientName');
            $table->date('ProjectSchedule');
            $table->unsignedBigInteger('UATHistoryId');
            $table->timestamps();

            $table->foreign('ProjectManagerId')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('UATHistoryId')->references('id')->on('uathistory')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
