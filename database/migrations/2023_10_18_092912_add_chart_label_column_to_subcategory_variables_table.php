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
        Schema::table('subcategory_variables', function (Blueprint $table) {
            $table->boolean('chart_label')->default(false)->after('first_column');
            $table->boolean('chart_data')->default(false)->after('first_column');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcategory_variables', function (Blueprint $table) {
            //
        });
    }
};
