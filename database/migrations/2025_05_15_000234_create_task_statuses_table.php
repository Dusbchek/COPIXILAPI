<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskStatusesTable extends Migration
{
    public function up(): void
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ej: pending, in_progress, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_statuses');
    }
}
