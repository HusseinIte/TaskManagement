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
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->increments('task_id');
            $table->string('title');
            $table->string('description');
            $table->string('priority')->default('High');
            $table->string('status')->default('New');
            $table->unsignedBigInteger('assigned_to');
            $table->foreign('assigned_to')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tasks');
    }
};
