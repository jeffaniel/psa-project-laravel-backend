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
        Schema::create('feedback_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->enum('status', ['draft', 'submitted', 'under_review', 'responded'])->default('submitted');
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->dateTime('submission_date')->useCurrent();
            $table->dateTime('response_date')->nullable();
            $table->longText('response_content')->nullable();
            $table->enum('analytics_color', ['black', 'white', 'red'])->default('black');
            $table->boolean('is_anonymous')->default(true);
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_submissions');
    }
};
