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
            $table->integer('isKey')->unsigned()->default(0)->change();
            $table->integer('required')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcategory_variable', function (Blueprint $table) {
            //
        });
    }
};
