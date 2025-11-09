<?php

use App\Enums\TaskPriority;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->mediumText('description')->nullable();
            $table->enum('priority', TaskPriority::values())->default(TaskPriority::LOW);

            $table->boolean('pinned')->default(false);
            $table->dateTime('done_at')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->foreignIdFor(User::class)
            ->nullable()
            ->constrained()
            ->nullOnDelete();

            $table->foreignIdFor(Category::class)
            ->nullable()
            ->constrained()
            ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
