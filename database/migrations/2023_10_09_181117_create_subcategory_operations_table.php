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
        Schema::create('subcategory_operations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subcategory_id');
            $table->foreignUuid('operation_id');
            $table->foreignUuid('variable_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategory_operations');
    }
};
