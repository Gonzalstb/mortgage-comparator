<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('age');
            $table->decimal('net_income', 10, 2);
            $table->string('contract_type');
            $table->decimal('available_savings', 10, 2);
            $table->decimal('monthly_expenses', 10, 2)->nullable();
            $table->integer('years_in_job')->nullable();
            $table->boolean('has_other_loans')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};