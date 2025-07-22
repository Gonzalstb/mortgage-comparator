<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortgage_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('min_amount', 12, 2);
            $table->decimal('max_amount', 12, 2);
            $table->decimal('max_ltv', 5, 2); // Loan to Value máximo
            $table->decimal('fixed_interest_rate', 5, 3)->nullable();
            $table->decimal('variable_interest_rate', 5, 3)->nullable();
            $table->string('variable_index')->nullable(); // Euribor + diferencial
            $table->decimal('differential', 5, 3)->nullable();
            $table->integer('min_term_years');
            $table->integer('max_term_years');
            $table->decimal('opening_commission', 5, 2)->default(0);
            $table->decimal('study_commission', 8, 2)->default(0);
            $table->decimal('early_cancellation_fee', 5, 2)->default(0);
            $table->json('requirements')->nullable();
            $table->json('linked_products')->nullable(); // productos vinculados
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortgage_products');
    }
};