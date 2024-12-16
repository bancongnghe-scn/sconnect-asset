<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_shopping_reports', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('quarter');
            $table->smallInteger('year');
            $table->bigInteger('budget_plan');
            $table->bigInteger('budget');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_shopping_reports');
    }
};
