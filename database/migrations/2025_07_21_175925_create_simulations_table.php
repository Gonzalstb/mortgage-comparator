<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference_code')->unique();
            $table->decimal('property_price', 12, 2);
            $table->decimal('loan_amount', 12, 2);
            $table->integer('term_years');
            $table->json('user_data'); // snapshot de datos del usuario
            $table->json('results'); // resultados de todos los bancos
            $table->boolean('is_favorite')->default(false);
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('is_favorite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};