<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('location', 150);
            $table->unsignedSmallInteger('required_hours')->default(1);
            $table->unsignedInteger('max_volunteers')->default(1);
            $table->enum('status', ['open', 'closed', 'completed'])->default('open')->index();
            $table->text('skills_required')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index('organization_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
