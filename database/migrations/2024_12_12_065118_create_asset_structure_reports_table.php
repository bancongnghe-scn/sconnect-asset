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
        Schema::create('asset_structure_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('org_id');
            $table->integer('quantity');
            $table->bigInteger('total_money');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->tinyInteger('type')->comment('Loại trạng thái');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_structure_reports');
    }
};
