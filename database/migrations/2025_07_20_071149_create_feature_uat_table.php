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
        Schema::create('feature_uat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ProjectId');
            $table->string('Feature');
            $table->date('UATDate');
            $table->enum('ValidationStatus', ['Worked', 'Failed']);
            $table->enum('ClientFeedbackStatus', ['Accepted', 'Revision']);
            $table->string('RevisionNotes', 500);
            $table->timestamps();

            $table->foreign('ProjectId')->references('id')->on('project')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_uat');
    }
};
