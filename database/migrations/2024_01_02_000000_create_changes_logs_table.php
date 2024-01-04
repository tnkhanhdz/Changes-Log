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
        Schema::create('changes_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('model');
            $table->bigInteger('record_id');
            $table->longText('changes');
            $table->bigInteger('changed_by')->nullable();
            $table->dateTime('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changes_logs');
    }
};
