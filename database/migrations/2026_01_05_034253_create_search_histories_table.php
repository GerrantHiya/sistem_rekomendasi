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
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id('ID_SearchHistory');
            $table->unsignedInteger('ID_Customers'); // Match customers table type
            $table->string('search_query', 255);
            $table->unsignedInteger('ID_Categories')->nullable();
            $table->unsignedInteger('ID_Brand')->nullable();
            $table->timestamp('searched_at')->useCurrent();
            
            $table->index(['ID_Customers', 'searched_at']);
            $table->index('search_query');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};
