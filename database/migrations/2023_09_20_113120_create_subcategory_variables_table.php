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
        Schema::create('subcategory_variables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subcategory_id');
            $table->foreignUuid('variable_id');
            $table->enum('required',['true','false'])->default('true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategory_variables');
    }
};
