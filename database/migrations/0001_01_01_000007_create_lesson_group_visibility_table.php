<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lesson_group_visibility', function (Blueprint $table) {
            $table->uuid('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->uuid('group_id')->constrained('groups')->cascadeOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->primary(['lesson_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_group_visibility');
    }
};
