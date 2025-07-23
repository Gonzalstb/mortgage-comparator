<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mortgage_products', function (Blueprint $table) {
            $table->timestamp('last_synced_at')->nullable()->after('is_active');
            $table->string('data_source')->default('manual')->after('last_synced_at'); // manual, api, scraping
            $table->decimal('last_tae', 5, 2)->nullable()->after('data_source');
        });
        
        Schema::table('banks', function (Blueprint $table) {
            $table->boolean('enable_api_sync')->default(false)->after('is_active');
            $table->boolean('enable_scraping')->default(true)->after('enable_api_sync');
            $table->timestamp('last_sync_attempt')->nullable()->after('enable_scraping');
        });
    }

    public function down(): void
    {
        Schema::table('mortgage_products', function (Blueprint $table) {
            $table->dropColumn(['last_synced_at', 'data_source', 'last_tae']);
        });
        
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['enable_api_sync', 'enable_scraping', 'last_sync_attempt']);
        });
    }
};