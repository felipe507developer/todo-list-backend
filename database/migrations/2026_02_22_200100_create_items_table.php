<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
