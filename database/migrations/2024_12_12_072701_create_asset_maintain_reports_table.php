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
        Schema::create('asset_maintain_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->bigInteger('total_money');
            $table->integer('plan_id');
            $table->string('plan_name');
            $table->smallInteger('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintain_reports');
    }
};
