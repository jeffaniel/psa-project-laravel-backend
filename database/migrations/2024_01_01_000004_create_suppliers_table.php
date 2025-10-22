<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->integer('payment_terms')->default(30); // days
            $table->json('bank_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
